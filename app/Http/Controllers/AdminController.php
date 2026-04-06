<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\Kategori;

class AdminController extends Controller
{
    public function dashboard()
{
    return view('admin.dashboard', [
        'totalPeminjaman'    => \App\Models\Peminjaman::count(),
        'sedangDipinjam'     => \App\Models\Peminjaman::where('status', 'dipinjam')->count(),
        'menungguKonfirmasi' => \App\Models\Peminjaman::where('status', 'menunggu')->count(),
        'totalAlat'          => \App\Models\Alat::count(),
        'alatTersedia'       => \App\Models\Alat::where('kondisi', 'baik')->count(),
        'alatPerbaikan'      => \App\Models\Alat::where('kondisi', 'perbaikan')->count(),
        'alatTidakTersedia'  => \App\Models\Alat::where('kondisi', 'rusak')->count(),
        'recentPeminjaman'   => \App\Models\Peminjaman::with(['user', 'alat'])->latest()->take(5)->get(),
        'pendingCount'       => \App\Models\Peminjaman::where('status', 'menunggu')->count(),
    ]);
}
}