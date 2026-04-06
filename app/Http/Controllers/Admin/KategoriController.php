<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::latest()->get();
        return view('admin.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'keterangan'    => 'nullable|string',
        ]);

        $kategori = Kategori::create($request->all());

        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'aktivitas'  => 'Menambah kategori: ' . $kategori->nama_kategori,
            'model'      => 'Kategori',
            'model_id'   => $kategori->id,
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'keterangan'    => 'nullable|string',
        ]);

        $kategori->update($request->all());

        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'aktivitas'  => 'Mengubah kategori: ' . $kategori->nama_kategori,
            'model'      => 'Kategori',
            'model_id'   => $kategori->id,
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diubah!');
    }

    public function destroy(Kategori $kategori)
    {
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'aktivitas'  => 'Menghapus kategori: ' . $kategori->nama_kategori,
            'model'      => 'Kategori',
            'model_id'   => $kategori->id,
        ]);

        $kategori->delete();

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus!');
    }
}