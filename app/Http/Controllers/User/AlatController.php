<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Alat;

class AlatController extends Controller
{
    public function index()
    {
        $alats = Alat::with('kategori')->where('kondisi', 'baik')->where('stok', '>', 0)->get();
        return view('user.alat.index', compact('alats'));
    }
}