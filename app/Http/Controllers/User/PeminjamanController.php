<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

    public function kembalikan(Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== Auth::id()) {
            abort(403);
        }
        return view('user.peminjaman.kembalikan', compact('peminjaman'));
    }

    public function konfirmasi(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'foto_bukti'           => 'required|image|max:2048',
            'tanggal_dikembalikan' => 'required|date',
            'catatan'              => 'nullable|string',
        ]);

        // Upload foto bukti
        $foto = $request->file('foto_bukti')->store('bukti', 'public');

        // Hitung denda jika terlambat
        $denda        = 0;
        $isTerlambat  = false;
        $dendaPerHari = 5000;

        if ($peminjaman->tanggal_kembali) {
            $batasKembali        = Carbon::parse($peminjaman->tanggal_kembali);
            $tanggalDikembalikan = Carbon::parse($request->tanggal_dikembalikan);

            if ($tanggalDikembalikan->gt($batasKembali)) {
                $hariTerlambat = $batasKembali->diffInDays($tanggalDikembalikan);
                $denda         = $hariTerlambat * $dendaPerHari;
                $isTerlambat   = true;
            }
        }

        $peminjaman->update([
            'foto_bukti'           => $foto,
            'tanggal_dikembalikan' => $request->tanggal_dikembalikan,
            'keterangan'           => $request->catatan,
            'denda'                => $denda,
            'is_terlambat'         => $isTerlambat,
            'status'               => 'menunggu',
        ]);

        $pesan = 'Bukti pengembalian berhasil dikirim! Menunggu verifikasi petugas.';
        if ($isTerlambat) {
            $pesan = '⚠️ Kamu terlambat! Denda Rp ' . number_format($denda, 0, ',', '.') . '. Menunggu verifikasi petugas.';
        }

        return redirect()->route('user.peminjaman.index')->with('success', $pesan);
    }
}