<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'alat'])
            ->where('status', 'dipinjam')
            ->latest()->get();
        return view('petugas.pengembalian.index', compact('peminjamans'));
    }

    public function verifikasi(Peminjaman $peminjaman)
    {
        $peminjaman->update([
            'status'          => 'dikembalikan',
            'tanggal_kembali' => Carbon::now()->toDateString(),
        ]);

        LogAktivitas::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Memverifikasi pengembalian: ' . $peminjaman->user->name,
            'model'     => 'Peminjaman',
            'model_id'  => $peminjaman->id,
        ]);

        return redirect()->route('petugas.pengembalian.index')->with('success', 'Pengembalian berhasil diverifikasi!');
    }
}