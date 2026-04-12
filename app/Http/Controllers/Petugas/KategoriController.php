<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::latest()->paginate(10);
        return view('petugas.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('petugas.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'keterangan'    => 'nullable|string',
        ]);

        Kategori::create($request->only('nama_kategori', 'keterangan'));

        return redirect()->route('petugas.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('petugas.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'keterangan'    => 'nullable|string',
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update($request->only('nama_kategori', 'keterangan'));

        return redirect()->route('petugas.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()->route('petugas.kategori.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}