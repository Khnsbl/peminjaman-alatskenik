@extends('layouts.petugas')
@section('title', 'Validasi Peminjaman')
@section('page-title', 'Validasi Peminjaman')

@section('content')
<div class="dark-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="mb-0 fw-bold" style="color: var(--text-primary)">Daftar Peminjaman</h6>
    </div>

    <table class="table dark-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Alat</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Keterangan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjamans as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <div style="color: var(--text-primary); font-weight: 500;">{{ $item->user->name ?? '-' }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $item->user->kelas ?? '' }} {{ $item->user->jurusan ?? '' }}</div>
                </td>
                <td>{{ $item->alat->nama_alat ?? '-' }}</td>
                <td>{{ $item->jumlah }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                <td style="font-size: 0.78rem;">{{ $item->keterangan ?? '-' }}</td>
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
                    @if($item->status == 'menunggu')
                        <form action="{{ route('petugas.peminjaman.approve', $item) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm me-1"
                                    style="background: rgba(16,185,129,0.15); color: #34d399; border-radius: 6px; font-size: 0.75rem;">
                                ✅ Setujui
                            </button>
                        </form>
                        <form action="{{ route('petugas.peminjaman.tolak', $item) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm"
                                    style="background: rgba(239,68,68,0.15); color: #f87171; border-radius: 6px; font-size: 0.75rem;">
                                ❌ Tolak
                            </button>
                        </form>
                    @else
                        <span style="color: var(--text-muted); font-size: 0.78rem;">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-4" style="color: var(--text-muted)">
                    Belum ada data peminjaman
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection