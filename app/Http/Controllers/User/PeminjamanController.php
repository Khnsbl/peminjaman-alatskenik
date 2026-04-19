<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    // ── Daftar peminjaman milik user ───────────────
    public function index()
    {
        $peminjaman = Peminjaman::with('alat')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.peminjaman.index', compact('peminjaman'));
    }

    // ── Form ajukan peminjaman ─────────────────────
    public function create(Request $request)
    {
        $alatId = $request->query('alat_id');

        $alat = $alatId
            ? Alat::where('stok', '>', 0)->findOrFail($alatId)
            : null;

        $alatList = Alat::where('stok', '>', 0)
            ->where('kondisi', '!=', 'rusak')
            ->where('kondisi', '!=', 'perbaikan')
            ->orderBy('nama_alat')
            ->get();

        return view('user.peminjaman.create', compact('alat', 'alatList'));
    }

    // ── Simpan pengajuan peminjaman ────────────────
    public function store(Request $request)
    {
        $request->validate([
            'alat_id'                 => 'required|exists:alats,id',
            'jumlah'                  => 'required|integer|min:1',
            'tanggal_pinjam'          => 'required|date|after_or_equal:today',
            'tanggal_rencana_kembali' => 'required|date|after:tanggal_pinjam',
            'keperluan'               => 'nullable|string|max:500',
        ]);

        $alat   = Alat::lockForUpdate()->findOrFail($request->alat_id);
        $jumlah = (int) $request->jumlah;

        if ($alat->stok < $jumlah) {
            return back()
                ->withInput()
                ->withErrors(['jumlah' => "Stok tidak mencukupi. Stok tersedia: {$alat->stok}"]);
        }

        DB::transaction(function () use ($request, $alat, $jumlah) {
            $peminjaman = Peminjaman::create([
                'user_id'         => Auth::id(),
                'alat_id'         => $alat->id,
                'jumlah'          => $jumlah,
                'tanggal_pinjam'  => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_rencana_kembali,
                'keterangan'      => $request->keperluan,
                'status'          => 'menunggu',
            ]);

            LogAktivitas::create([
                'user_id'   => Auth::id(),
                'aktivitas' => 'Mengajukan peminjaman: ' . $alat->nama_alat . ' (×' . $jumlah . ')',
                'model'     => 'Peminjaman',
                'model_id'  => $peminjaman->id,
            ]);
        });

        return redirect()
            ->route('user.peminjaman.index')
            ->with('success', 'Pengajuan peminjaman berhasil! Menunggu konfirmasi petugas.');
    }

    // ── Form pengembalian ─────────────────────────
    public function formKembalikan($id)
    {
        $peminjaman = Peminjaman::with('alat')
            ->where('user_id', Auth::id())
            ->where('id', $id)
            ->where('status', 'dipinjam')
            ->firstOrFail();

        return view('user.peminjaman.kembalikan', compact('peminjaman'));
    }

    // ── Proses pengembalian ───────────────────────
    public function kembalikan(Request $request, $id)
    {
        $request->validate([
            'tanggal_dikembalikan' => 'required|date',
            'kondisi_kembali'      => 'required|in:baik,rusak_ringan,rusak_berat',
            'keterangan_kembali'   => 'nullable|string|max:500',
            'foto_bukti'           => 'required|image|max:2048',
        ]);

        $peminjaman = Peminjaman::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('status', 'dipinjam')
            ->firstOrFail();

        // Upload foto
        $fotoPath = $request->file('foto_bukti')->store('foto_bukti', 'public');

        $peminjaman->update([
            'status'               => 'menunggu_verifikasi',
            'tanggal_dikembalikan' => $request->tanggal_dikembalikan,
            'keterangan'           => $request->keterangan_kembali,
            'foto_bukti'           => $fotoPath,
            'kondisi_kembali'      => $request->kondisi_kembali,
        ]);

        LogAktivitas::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Mengajukan pengembalian: ' . $peminjaman->alat->nama_alat,
            'model'     => 'Peminjaman',
            'model_id'  => $peminjaman->id,
        ]);

        return redirect()
            ->route('user.peminjaman.index')
            ->with('success', 'Pengembalian berhasil diajukan! Menunggu verifikasi petugas.');
    }

    // ── Cek stok via AJAX ─────────────────────────
    public function cekStok(Request $request)
    {
        $alat = Alat::find($request->alat_id);

        if (!$alat) {
            return response()->json(['error' => 'Alat tidak ditemukan'], 404);
        }

        return response()->json([
            'stok'       => $alat->stok,
            'kondisi'    => $alat->kondisi,
            'tersedia'   => $alat->stokTersedia(),
            'persentase' => $alat->persentaseStok(),
            'nama'       => $alat->nama_alat,
        ]);
    }
}