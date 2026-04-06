<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with('alat')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
        return view('user.peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        $alats = Alat::where('kondisi', 'baik')->where('stok', '>', 0)->get();
        return view('user.peminjaman.create', compact('alats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'alat_id'        => 'required',
            'jumlah'         => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'keterangan'     => 'nullable|string',
        ]);

        Peminjaman::create([
            'user_id'        => Auth::id(),
            'alat_id'        => $request->alat_id,
            'jumlah'         => $request->jumlah,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'status'         => 'menunggu',
            'keterangan'     => $request->keterangan,
        ]);

        return redirect()->route('user.peminjaman.index')->with('success', 'Peminjaman berhasil diajukan!');
    }
}