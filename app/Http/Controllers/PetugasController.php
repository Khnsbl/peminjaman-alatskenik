<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Alat;

class PetugasController extends Controller
{
    public function dashboard()
    {
        return view('petugas.dashboard', [
            'totalPeminjaman'    => Peminjaman::count(),
            'menungguValidasi'   => Peminjaman::where('status', 'menunggu')->count(),
            'sedangDipinjam'     => Peminjaman::where('status', 'dipinjam')->count(),
            'totalAlat'          => Alat::count(),
            'pendingCount'       => Peminjaman::where('status', 'menunggu')->count(),
            'recentPeminjaman'   => Peminjaman::with(['user', 'alat'])->latest()->take(5)->get(),
        ]);
    }
}