<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'alat'])
            ->whereIn('status', ['menunggu_verifikasi', 'perlu_bayar_denda'])
            ->latest()
            ->get();

        return view('petugas.pengembalian.index', compact('peminjamans'));
    }

    public function verifikasi(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'aksi'  => 'required|in:selesai,denda',
            'denda' => 'required_if:aksi,denda|nullable|integer|min:0',
        ]);

        if ($request->aksi === 'denda') {
            // Kenakan denda manual
            $peminjaman->update([
                'status'       => 'perlu_bayar_denda',
                'denda'        => $request->denda,
                'is_terlambat' => true,
            ]);

            LogAktivitas::create([
                'user_id'   => Auth::id(),
                'aktivitas' => 'Mengenakan denda pengembalian: ' . $peminjaman->user->name . ' - Rp ' . number_format($request->denda, 0, ',', '.'),
                'model'     => 'Peminjaman',
                'model_id'  => $peminjaman->id,
            ]);

            return redirect()->route('petugas.pengembalian.index')
                ->with('success', '⚠️ Denda Rp ' . number_format($request->denda, 0, ',', '.') . ' telah dikenakan.');
        }

        // Selesai tanpa denda — kembalikan stok
        $peminjaman->alat->increment('stok', $peminjaman->jumlah);

        $peminjaman->update([
            'status'       => 'dikembalikan',
            'denda'        => 0,
            'is_terlambat' => false,
        ]);

        LogAktivitas::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Memverifikasi pengembalian: ' . $peminjaman->user->name,
            'model'     => 'Peminjaman',
            'model_id'  => $peminjaman->id,
        ]);

        return redirect()->route('petugas.pengembalian.index')
            ->with('success', '✅ Pengembalian berhasil diverifikasi!');
    }

    public function konfirmasiDenda(Peminjaman $peminjaman)
    {
        // Denda lunas — kembalikan stok
        $peminjaman->alat->increment('stok', $peminjaman->jumlah);

        $peminjaman->update(['status' => 'dikembalikan']);

        LogAktivitas::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Konfirmasi denda dibayar: ' . $peminjaman->user->name . ' - Rp ' . number_format($peminjaman->denda, 0, ',', '.'),
            'model'     => 'Peminjaman',
            'model_id'  => $peminjaman->id,
        ]);

        return redirect()->route('petugas.pengembalian.index')
            ->with('success', '✅ Denda dikonfirmasi lunas! Peminjaman selesai.');
    }
}