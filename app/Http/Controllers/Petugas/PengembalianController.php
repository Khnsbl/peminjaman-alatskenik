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
        $dendaPerHari = 5000;
        $denda        = 0;
        $isTerlambat  = false;

        if ($peminjaman->tanggal_kembali && $peminjaman->tanggal_dikembalikan) {
            $batas       = Carbon::parse($peminjaman->tanggal_kembali);
            $dikembalikan = Carbon::parse($peminjaman->tanggal_dikembalikan);

            if ($dikembalikan->gt($batas)) {
                $hariTerlambat = $batas->diffInDays($dikembalikan);
                $denda         = $hariTerlambat * $dendaPerHari;
                $isTerlambat   = true;
            }
        }

        if ($isTerlambat) {
            $peminjaman->update([
                'status'       => 'perlu_bayar_denda',
                'denda'        => $denda,
                'is_terlambat' => true,
            ]);

            LogAktivitas::create([
                'user_id'   => Auth::id(),
                'aktivitas' => 'Memverifikasi pengembalian terlambat: ' . $peminjaman->user->name . ' - Denda Rp ' . number_format($denda, 0, ',', '.'),
                'model'     => 'Peminjaman',
                'model_id'  => $peminjaman->id,
            ]);

            return redirect()->route('petugas.pengembalian.index')
                ->with('success', '⚠️ Pengembalian terlambat! Denda Rp ' . number_format($denda, 0, ',', '.') . ' telah dikenakan.');
        }

        $peminjaman->update([
            'status'           => 'dikembalikan',
            'tanggal_kembali'  => $peminjaman->tanggal_dikembalikan,
            'denda'            => 0,
            'is_terlambat'     => false,
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