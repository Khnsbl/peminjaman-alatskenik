<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Alat;
use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'alat'])->latest()->get();
        return view('admin.peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        $alats = Alat::all();
        $users = User::where('role', 'user')->get();
        return view('admin.peminjaman.create', compact('alats', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'        => 'required',
            'alat_id'        => 'required',
            'jumlah'         => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'keterangan'     => 'nullable|string',
        ]);

        $peminjaman = Peminjaman::create([
            'user_id'        => $request->user_id,
            'alat_id'        => $request->alat_id,
            'jumlah'         => $request->jumlah,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'status'         => 'menunggu',
            'keterangan'     => $request->keterangan,
        ]);

        LogAktivitas::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Menambah peminjaman untuk: ' . $peminjaman->user->name,
            'model'     => 'Peminjaman',
            'model_id'  => $peminjaman->id,
        ]);

        return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman berhasil ditambahkan!');
    }

    public function edit(Peminjaman $peminjaman)
    {
        $alats = Alat::all();
        $users = User::where('role', 'user')->get();
        return view('admin.peminjaman.edit', compact('peminjaman', 'alats', 'users'));
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'status'           => 'required|in:menunggu,dipinjam,dikembalikan,ditolak',
            'tanggal_kembali'  => 'nullable|date',
            'keterangan'       => 'nullable|string',
        ]);

        $peminjaman->update($request->all());

        LogAktivitas::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Mengubah status peminjaman: ' . $peminjaman->user->name,
            'model'     => 'Peminjaman',
            'model_id'  => $peminjaman->id,
        ]);

        return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman berhasil diupdate!');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        LogAktivitas::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Menghapus peminjaman: ' . $peminjaman->user->name,
            'model'     => 'Peminjaman',
            'model_id'  => $peminjaman->id,
        ]);

        $peminjaman->delete();

        return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman berhasil dihapus!');
    }
}