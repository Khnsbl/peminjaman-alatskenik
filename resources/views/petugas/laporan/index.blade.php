@extends('layouts.petugas')
@section('title', 'Laporan')
@section('page-title', 'Laporan Peminjaman')

@section('content')
<div class="dark-card mb-4">
    {{-- Filter --}}
    <form method="GET" action="{{ route('petugas.laporan.index') }}" class="row g-2 align-items-end mb-4">
        <div class="col-auto">
            <label class="form-label" style="font-size: 0.82rem; color: var(--text-secondary);">Bulan</label>
            <select name="bulan" class="form-select form-select-sm"
                    style="background: var(--main-bg); border-color: var(--card-border); color: var(--text-primary); font-size: 0.85rem;">
                <option value="">Semua Bulan</option>
                @foreach(range(1,12) as $b)
                    <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <label class="form-label" style="font-size: 0.82rem; color: var(--text-secondary);">Tahun</label>
            <select name="tahun" class="form-select form-select-sm"
                    style="background: var(--main-bg); border-color: var(--card-border); color: var(--text-primary); font-size: 0.85rem;">
                @foreach(range(date('Y'), 2024) as $t)
                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-sm px-3"
                    style="background: var(--accent); color: #fff; border-radius: 8px; font-size: 0.82rem;">
                Filter
            </button>
        </div>
        <div class="col-auto">
            <a href="{{ route('petugas.laporan.cetak', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
               class="btn btn-sm px-3"
               style="background: rgba(34,197,94,0.15); color: #4ade80; border-radius: 8px; font-size: 0.82rem; border: 1px solid rgba(34,197,94,0.3);">
                ⬇ Export Excel
            </a>
        </div>
    </form>

    {{-- Statistik --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="p-3 rounded text-center" style="background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.2);">
                <div style="font-size: 1.4rem; font-weight: 700; color: #818cf8;">{{ $totalPeminjaman }}</div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">Total Peminjaman</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3 rounded text-center" style="background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.2);">
                <div style="font-size: 1.4rem; font-weight: 700; color: #4ade80;">{{ $totalDikembalikan }}</div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">Dikembalikan</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3 rounded text-center" style="background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.2);">
                <div style="font-size: 1.4rem; font-weight: 700; color: #fbbf24;">{{ $totalMenunggu }}</div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">Menunggu</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3 rounded text-center" style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2);">
                <div style="font-size: 1.4rem; font-weight: 700; color: #f87171;">{{ $totalDitolak }}</div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">Ditolak</div>
            </div>
        </div>
    </div>

    {{-- Tabel --}}
    <table class="table dark-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Alat</th>
                <th>Kategori</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjamans as $index => $p)
            @php
                $terlambat = 0;
                $denda = 0;
                if ($p->tanggal_kembali && $p->status === 'dikembalikan') {
                    $rencana = \Carbon\Carbon::parse($p->tanggal_kembali);
                    $aktual  = \Carbon\Carbon::parse($p->updated_at->toDateString());
                    $terlambat = max(0, $aktual->diffInDays($rencana, false) * -1);
                    $denda = $terlambat * 5000;
                }
            @endphp
            <tr>
                <td>{{ $peminjamans->firstItem() + $index }}</td>
                <td style="color: var(--text-primary)">{{ $p->user->name ?? '-' }}</td>
                <td style="color: var(--text-primary)">{{ $p->alat->nama_alat ?? '-' }}</td>
                <td style="color: var(--text-muted)">{{ $p->alat->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ $p->jumlah }}</td>
                <td style="color: var(--text-muted); font-size: 0.8rem;">{{ $p->tanggal_pinjam }}</td>
                <td style="color: var(--text-muted); font-size: 0.8rem;">{{ $p->tanggal_kembali ?? '-' }}</td>
                <td>
                    @if($p->status == 'menunggu')
                        <span class="status-badge badge-yellow">Menunggu</span>
                    @elseif($p->status == 'dipinjam')
                        <span class="status-badge badge-blue">Dipinjam</span>
                    @elseif($p->status == 'dikembalikan')
                        <span class="status-badge badge-green">Dikembalikan</span>
                    @else
                        <span class="status-badge badge-red">Ditolak</span>
                    @endif
                </td>
                <td style="color: {{ $denda > 0 ? '#f87171' : 'var(--text-muted)' }}; font-size: 0.8rem;">
                    {{ $denda > 0 ? 'Rp ' . number_format($denda, 0, ',', '.') : '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-4" style="color: var(--text-muted)">
                    Belum ada data laporan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $peminjamans->appends(request()->query())->links() }}
    </div>
</div>
@endsection