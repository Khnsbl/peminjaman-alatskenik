<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        return view('user.dashboard', [
            'totalPeminjaman' => Peminjaman::where('user_id', $userId)->count(),
            'menunggu'        => Peminjaman::where('user_id', $userId)->where('status', 'menunggu')->count(),
            'dipinjam'        => Peminjaman::where('user_id', $userId)->where('status', 'dipinjam')->count(),
            'dikembalikan'    => Peminjaman::where('user_id', $userId)->where('status', 'dikembalikan')->count(),
            'recentPeminjaman'=> Peminjaman::with('alat')->where('user_id', $userId)->latest()->take(5)->get(),
        ]);
    }
}