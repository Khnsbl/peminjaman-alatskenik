@extends('layouts.user')
@section('title', 'Peminjaman Saya')
@section('page-title', 'Peminjaman Saya')

@section('content')

<style>
    .pagination {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .pagination .page-item .page-link {
        background: rgba(255,255,255,0.05);
        border: 1px solid var(--card-border);
        color: var(--text-secondary);
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 0.82rem;
        text-decoration: none;
        transition: all 0.15s;
        display: inline-block;
    }
    .pagination .page-item .page-link:hover {
        background: rgba(99,102,241,0.15);
        border-color: var(--accent);
        color: var(--accent);
    }
    .pagination .page-item.active .page-link {
        background: var(--accent);
        border-color: var(--accent);
        color: #fff;
        font-weight: 600;
    }
    .pagination .page-item.disabled .page-link {
        opacity: 0.35;
        cursor: not-allowed;
        pointer-events: none;
    }
    .pagination-info {
        font-size: 0.78rem;
        color: var(--text-muted);
        text-align: center;
        margin-top: 8px;
    }
    .btn-kembalikan {
        background: rgba(16,185,129,0.15);
        color: #34d399;
        border: 1px solid rgba(16,185,129,0.3);
        border-radius: 6px;
        padding: 4px 10px;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.15s;
        white-space: nowrap;
    }
    .btn-kembalikan:hover {
        background: rgba(16,185,129,0.25);
    }
</style>

{{-- Notifikasi Denda --}}
@php $dendaList = $peminjaman->where('status', 'perlu_bayar_denda'); @endphp
@if($dendaList->count() > 0)
<div class="mb-4 p-4 rounded" style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3);">
    <div style="color: #f87171; font-weight: 600; font-size: 0.95rem; margin-bottom: 8px;">
        ⚠️ Kamu memiliki {{ $dendaList->count() }} denda yang harus dibayar!
    </div>
    @foreach($dendaList as $denda)
    <div style="background: rgba(239,68,68,0.08); border-radius: 8px; padding: 10px 12px; margin-bottom: 6px;">
        <div style="color: #fca5a5; font-size: 0.85rem;">
            🔧 <strong>{{ $denda->alat->nama_alat }}</strong> —
            Denda: <strong>Rp {{ number_format($denda->denda, 0, ',', '.') }}</strong>
        </div>
        <div style="color: var(--text-muted); font-size: 0.75rem; margin-top: 2px;">
            Segera hubungi petugas untuk melunasi denda
        </div>
    </div>
    @endforeach
</div>
@endif

<div class="dark-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="mb-0 fw-bold" style="color: var(--text-primary)">Riwayat Peminjaman</h6>
        <a href="{{ route('user.peminjaman.create') }}" class="btn btn-sm px-3"
           style="background: var(--accent); color: #fff; border-radius: 8px; font-size: 0.82rem;">
            + Ajukan Peminjaman
        </a>
    </div>

    <table class="table dark-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Alat</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Keterangan</th>
                <th>Status</th>
                <th>Denda</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjaman as $index => $item)
            <tr>
                <td>{{ $peminjaman->firstItem() + $index }}</td>
                <td style="color: var(--text-primary)">{{ $item->alat->nama_alat ?? '-' }}</td>
                <td>{{ $item->jumlah ?? 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>

                {{-- TGL KEMBALI --}}
                <td>
                    @if($item->tanggal_kembali)
                        @php
                            $terlambat = $item->status == 'dipinjam'
                                && \Carbon\Carbon::parse($item->tanggal_kembali)->isPast();
                        @endphp
                        <span style="color: {{ $terlambat ? '#f87171' : 'var(--text-secondary)' }}">
                            {{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') }}
                            @if($terlambat)
                                <br><span style="font-size: 0.7rem;">⚠️ Terlambat</span>
                            @endif
                        </span>
                    @else
                        <span style="color: var(--text-muted)">-</span>
                    @endif
                </td>

                {{-- KETERANGAN --}}
                <td style="font-size: 0.78rem; max-width: 140px; color: var(--text-muted);">
                    {{ $item->keterangan ?? '-' }}
                </td>

                {{-- STATUS --}}
                <td>
                    @if($item->status == 'menunggu')
                        <span class="status-badge badge-yellow">Menunggu</span>
                    @elseif($item->status == 'dipinjam')
                        <span class="status-badge badge-blue">Dipinjam</span>
                    @elseif($item->status == 'menunggu_verifikasi')
                        <span class="status-badge badge-yellow">Menunggu Verifikasi</span>
                    @elseif($item->status == 'perlu_bayar_denda')
                        <span class="status-badge badge-red">Perlu Bayar Denda</span>
                    @elseif($item->status == 'dikembalikan')
                        <span class="status-badge badge-green">Selesai</span>
                    @elseif($item->status == 'ditolak')
                        <span class="status-badge badge-red">Ditolak</span>
                    @else
                        <span class="status-badge badge-red">{{ $item->status }}</span>
                    @endif
                </td>

                {{-- DENDA --}}
                <td>
                    @if(($item->denda ?? 0) > 0)
                        <span style="color: #f87171; font-weight: 600; font-size: 0.82rem;">
                            Rp {{ number_format($item->denda, 0, ',', '.') }}
                        </span>
                    @else
                        <span style="color: var(--text-muted); font-size: 0.78rem;">-</span>
                    @endif
                </td>

                {{-- AKSI --}}
                <td>
                    @if($item->status == 'dipinjam')
                        <form action="{{ route('user.peminjaman.kembalikan', $item->id) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin mengajukan pengembalian?')">
                            @csrf
                            <button type="submit" class="btn-kembalikan">
                                🔄 Kembalikan
                            </button>
                        </form>
                    @elseif($item->status == 'menunggu')
                        <span style="color: var(--text-muted); font-size: 0.75rem;">Menunggu konfirmasi</span>
                    @elseif($item->status == 'menunggu_verifikasi')
                        <span style="color: #fbbf24; font-size: 0.75rem;">Menunggu verifikasi petugas</span>
                    @else
                        <span style="color: var(--text-muted); font-size: 0.78rem;">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-4" style="color: var(--text-muted)">
                    Belum ada peminjaman
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    @if($peminjaman->hasPages())
    <div class="mt-4">
        {{ $peminjaman->links() }}
        <div class="pagination-info">
            Menampilkan {{ $peminjaman->firstItem() }}–{{ $peminjaman->lastItem() }}
            dari {{ $peminjaman->total() }} data
        </div>
    </div>
    @endif
</div>

@endsection