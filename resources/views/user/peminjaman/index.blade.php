@extends('layouts.user')
@section('title', 'Peminjaman Saya')
@section('page-title', 'Peminjaman Saya')

@section('content')

{{-- Notifikasi Denda --}}
@php $dendaList = $peminjamans->where('status', 'perlu_bayar_denda'); @endphp
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
                <th>Status</th>
                <th>Denda</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjamans as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td style="color: var(--text-primary)">{{ $item->alat->nama_alat ?? '-' }}</td>
                <td>{{ $item->jumlah }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                <td>
                    @if($item->tanggal_kembali)
                        <span style="color: {{ $item->status == 'dipinjam' && \Carbon\Carbon::parse($item->tanggal_kembali)->isPast() ? '#f87171' : 'var(--text-secondary)' }}">
                            {{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') }}
                            @if($item->status == 'dipinjam' && \Carbon\Carbon::parse($item->tanggal_kembali)->isPast())
                                <span style="font-size: 0.7rem;">⚠️ Terlambat</span>
                            @endif
                        </span>
                    @else
                        -
                    @endif
                </td>
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
                    @else
                        <span class="status-badge badge-red">Ditolak</span>
                    @endif
                </td>
                <td>
                    @if($item->denda > 0)
                        <span style="color: #f87171; font-weight: 600; font-size: 0.82rem;">
                            Rp {{ number_format($item->denda, 0, ',', '.') }}
                        </span>
                    @else
                        <span style="color: var(--text-muted); font-size: 0.78rem;">-</span>
                    @endif
                </td>
                <td>
                    @if($item->status == 'dipinjam')
                        <a href="{{ route('user.peminjaman.kembalikan', $item) }}"
                           class="btn btn-sm"
                           style="background: rgba(16,185,129,0.15); color: #34d399; border-radius: 6px; font-size: 0.75rem;">
                            Kembalikan
                        </a>
                    @else
                        <span style="color: var(--text-muted); font-size: 0.78rem;">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-4" style="color: var(--text-muted)">
                    Belum ada peminjaman
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection