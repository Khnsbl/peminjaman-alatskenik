@extends('layouts.user')
@section('title', 'Peminjaman Saya')
@section('page-title', 'Peminjaman Saya')

@section('content')
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
                <td>{{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') : '-' }}</td>
                <td>
                    @if($item->status == 'menunggu')
                        <span class="status-badge badge-yellow">Menunggu</span>
                    @elseif($item->status == 'dipinjam')
                        <span class="status-badge badge-blue">Dipinjam</span>
                    @elseif($item->status == 'dikembalikan')
                        <span class="status-badge badge-green">Dikembalikan</span>
                    @else
                        <span class="status-badge badge-red">Ditolak</span>
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
                <td colspan="7" class="text-center py-4" style="color: var(--text-muted)">
                    Belum ada peminjaman
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection