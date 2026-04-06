<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'alat'])->latest()->get();
        return view('petugas.peminjaman.index', compact('peminjamans'));
    }

    public function approve(Peminjaman $peminjaman)
    {
        $peminjaman->update(['status' => 'dipinjam']);

        LogAktivitas::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Menyetujui peminjaman: ' . $peminjaman->user->name,
            'model'     => 'Peminjaman',
            'model_id'  => $peminjaman->id,
        ]);

        return redirect()->route('petugas.peminjaman.index')->with('success', 'Peminjaman disetujui!');
    }

    public function tolak(Peminjaman $peminjaman)
    {
        $peminjaman->update(['status' => 'ditolak']);

        LogAktivitas::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Menolak peminjaman: ' . $peminjaman->user->name,
            'model'     => 'Peminjaman',
            'model_id'  => $peminjaman->id,
        ]);

        return redirect()->route('petugas.peminjaman.index')->with('success', 'Peminjaman ditolak!');
    }
}