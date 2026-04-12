@extends('layouts.petugas')
@section('title', 'Verifikasi Pengembalian')
@section('page-title', 'Verifikasi Pengembalian')

@section('content')
<div class="dark-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="mb-0 fw-bold" style="color: var(--text-primary)">Alat Sedang Dipinjam</h6>
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
                    <form action="{{ route('petugas.pengembalian.verifikasi', $item) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm"
                                style="background: rgba(16,185,129,0.15); color: #34d399; border-radius: 6px; font-size: 0.75rem;">
                            ✅ Verifikasi Kembali
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-4" style="color: var(--text-muted)">
                    Tidak ada alat yang sedang dipinjam
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection