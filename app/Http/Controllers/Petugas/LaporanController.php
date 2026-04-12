<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun ?? date('Y');

        $query = Peminjaman::with(['user', 'alat.kategori']);

        if ($bulan && $tahun) {
            $query->whereMonth('tanggal_pinjam', $bulan)
                  ->whereYear('tanggal_pinjam', $tahun);
        } elseif ($tahun) {
            $query->whereYear('tanggal_pinjam', $tahun);
        }

        $peminjamans = $query->orderBy('tanggal_pinjam')->paginate(10);

        $totalPeminjaman = Peminjaman::when($bulan, fn($q) => $q->whereMonth('tanggal_pinjam', $bulan))
            ->whereYear('tanggal_pinjam', $tahun)->count();
        $totalDikembalikan = Peminjaman::where('status', 'dikembalikan')
            ->when($bulan, fn($q) => $q->whereMonth('tanggal_pinjam', $bulan))
            ->whereYear('tanggal_pinjam', $tahun)->count();
        $totalMenunggu = Peminjaman::where('status', 'menunggu')
            ->when($bulan, fn($q) => $q->whereMonth('tanggal_pinjam', $bulan))
            ->whereYear('tanggal_pinjam', $tahun)->count();
        $totalDitolak = Peminjaman::where('status', 'ditolak')
            ->when($bulan, fn($q) => $q->whereMonth('tanggal_pinjam', $bulan))
            ->whereYear('tanggal_pinjam', $tahun)->count();

        return view('petugas.laporan.index', compact(
            'peminjamans', 'bulan', 'tahun',
            'totalPeminjaman', 'totalDikembalikan', 'totalMenunggu', 'totalDitolak'
        ));
    }

    public function cetak(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun ?? date('Y');

        $query = Peminjaman::with(['user', 'alat.kategori']);

        if ($bulan && $tahun) {
            $query->whereMonth('tanggal_pinjam', $bulan)
                  ->whereYear('tanggal_pinjam', $tahun);
        } elseif ($tahun) {
            $query->whereYear('tanggal_pinjam', $tahun);
        }

        $peminjamans = $query->orderBy('tanggal_pinjam')->get();

        $namaBulan = $bulan ? \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') : 'Semua';
        $filename  = "laporan-peminjaman-{$namaBulan}-{$tahun}.csv";

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($peminjamans) {
            $handle = fopen('php://output', 'w');

            // BOM agar Excel baca UTF-8 dengan benar
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No', 'Nama Peminjam', 'Nama Alat', 'Kategori',
                'Jumlah', 'Tanggal Pinjam', 'Tanggal Kembali',
                'Status', 'Keterlambatan (hari)', 'Denda (Rp)', 'Keterangan'
            ], ';');

            foreach ($peminjamans as $i => $p) {
                $terlambat = 0;
                $denda     = 0;

                if ($p->tanggal_kembali && $p->status === 'dikembalikan') {
                    $rencana   = \Carbon\Carbon::parse($p->tanggal_kembali);
                    $aktual    = \Carbon\Carbon::parse($p->updated_at->toDateString());
                    $terlambat = max(0, $aktual->diffInDays($rencana, false) * -1);
                    $denda     = $terlambat * 5000;
                }

                fputcsv($handle, [
                    $i + 1,
                    $p->user->name ?? '-',
                    $p->alat->nama_alat ?? '-',
                    $p->alat->kategori->nama_kategori ?? '-',
                    $p->jumlah,
                    $p->tanggal_pinjam,
                    $p->tanggal_kembali ?? '-',
                    ucfirst($p->status),
                    $terlambat > 0 ? $terlambat : '-',
                    $denda > 0 ? 'Rp ' . number_format($denda, 0, ',', '.') : '-',
                    $p->keterangan ?? '-',
                ], ';');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}