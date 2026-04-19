<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with(['user', 'alat'])->latest()->get();
        return view('petugas.peminjaman.index', compact('peminjaman'));
    }

    public function approve(Peminjaman $peminjaman)
    {
        DB::transaction(function () use ($peminjaman) {
            $alat = $peminjaman->alat;

            // Cek stok cukup
            if ($alat->stok < $peminjaman->jumlah) {
                return redirect()->route('petugas.peminjaman.index')
                    ->with('error', 'Stok tidak mencukupi untuk menyetujui peminjaman ini.');
            }

            // Kurangi stok
            $alat->decrement('stok', $peminjaman->jumlah);

            // Update status
            $peminjaman->update(['status' => 'dipinjam']);

            LogAktivitas::create([
                'user_id'   => Auth::id(),
                'aktivitas' => 'Menyetujui peminjaman: ' . $peminjaman->user->name . ' (' . $alat->nama_alat . ' ×' . $peminjaman->jumlah . ')',
                'model'     => 'Peminjaman',
                'model_id'  => $peminjaman->id,
            ]);
        });

        return redirect()->route('petugas.peminjaman.index')
            ->with('success', '✅ Peminjaman berhasil disetujui!');
    }

    public function tolak(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'alasan_tolak' => 'required|string|max:255',
        ]);

        $peminjaman->update([
            'status'     => 'ditolak',
            'keterangan' => $request->alasan_tolak,
        ]);

        LogAktivitas::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Menolak peminjaman: ' . $peminjaman->user->name . ' - Alasan: ' . $request->alasan_tolak,
            'model'     => 'Peminjaman',
            'model_id'  => $peminjaman->id,
        ]);

        return redirect()->route('petugas.peminjaman.index')
            ->with('success', 'Peminjaman ditolak!');
    }
}