<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AkunController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->get();
        return view('admin.akun.index', compact('users'));
    }

    public function create()
    {
        return view('admin.akun.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required|in:petugas,user',
        ];

        // Validasi NISN, kelas, jurusan hanya untuk siswa
        if ($request->role === 'user') {
            $rules['nisn']    = 'required|string|max:20|unique:users';
            $rules['kelas']   = 'required|string|max:20';
            $rules['jurusan'] = 'required|string|max:100';
        }

        $request->validate($rules);

        User::create([
            'name'     => $request->name,
            'nisn'     => $request->role === 'user' ? $request->nisn : null,
            'kelas'    => $request->role === 'user' ? $request->kelas : null,
            'jurusan'  => $request->role === 'user' ? $request->jurusan : null,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('admin.akun.index')->with('success', 'Akun berhasil dibuat!');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('admin.akun.index')->with('success', 'Akun berhasil dihapus!');
    }
}