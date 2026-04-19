<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AlatController extends Controller
{
    public function index()
    {
        $alats = Alat::with('kategori')->latest()->get();
        return view('admin.alat.index', compact('alats'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('admin.alat.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_alat'   => 'required|unique:alats',
            'nama_alat'   => 'required|string|max:255',
            'kategori_id' => 'required',
            'stok'        => 'required|integer|min:0',
            'kondisi'     => 'required|in:baik,rusak,perbaikan',
            'foto'        => 'nullable|image|max:2048',
        ]);

        $foto = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('alat', 'public');
        }

        $alat = Alat::create([
            'kode_alat'   => $request->kode_alat,
            'nama_alat'   => $request->nama_alat,
            'kategori_id' => $request->kategori_id,
            'stok'        => $request->stok,
            'stok_awal'   => $request->stok, // ← TAMBAHAN: otomatis sama dengan stok awal input
            'kondisi'     => $request->kondisi,
            'foto'        => $foto,
        ]);

        LogAktivitas::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Menambah alat: ' . $alat->nama_alat,
            'model'     => 'Alat',
            'model_id'  => $alat->id,
        ]);

        return redirect()->route('admin.alat.index')->with('success', 'Alat berhasil ditambahkan!');
    }

    public function edit(Alat $alat)
    {
        $kategoris = Kategori::all();
        return view('admin.alat.edit', compact('alat', 'kategoris'));
    }

    public function update(Request $request, Alat $alat)
    {
        $request->validate([
            'kode_alat'   => 'required|unique:alats,kode_alat,' . $alat->id,
            'nama_alat'   => 'required|string|max:255',
            'kategori_id' => 'required',
            'stok'        => 'required|integer|min:0',
            'kondisi'     => 'required|in:baik,rusak,perbaikan',
            'foto'        => 'nullable|image|max:2048',
        ]);

        $foto = $alat->foto;
        if ($request->hasFile('foto')) {
            if ($foto) Storage::disk('public')->delete($foto);
            $foto = $request->file('foto')->store('alat', 'public');
        }

        $alat->update([
            'kode_alat'   => $request->kode_alat,
            'nama_alat'   => $request->nama_alat,
            'kategori_id' => $request->kategori_id,
            'stok'        => $request->stok,
            'stok_awal'   => $alat->stok_awal, // ← TAMBAHAN: stok_awal tidak berubah saat edit
            'kondisi'     => $request->kondisi,
            'foto'        => $foto,
        ]);

        LogAktivitas::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Mengubah alat: ' . $alat->nama_alat,
            'model'     => 'Alat',
            'model_id'  => $alat->id,
        ]);

        return redirect()->route('admin.alat.index')->with('success', 'Alat berhasil diubah!');
    }

    public function destroy(Alat $alat)
    {
        if ($alat->foto) Storage::disk('public')->delete($alat->foto);

        LogAktivitas::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Menghapus alat: ' . $alat->nama_alat,
            'model'     => 'Alat',
            'model_id'  => $alat->id,
        ]);

        $alat->delete();

        return redirect()->route('admin.alat.index')->with('success', 'Alat berhasil dihapus!');
    }
}