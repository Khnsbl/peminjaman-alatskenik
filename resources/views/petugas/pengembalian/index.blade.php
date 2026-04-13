@extends('layouts.petugas')
@section('title', 'Verifikasi Pengembalian')
@section('page-title', 'Verifikasi Pengembalian')

@section('content')
<div class="dark-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="mb-0 fw-bold" style="color: var(--text-primary)">Daftar Pengembalian</h6>
    </div>

    <table class="table dark-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Alat</th>
                <th>Tgl Pinjam</th>
                <th>Batas Kembali</th>
                <th>Tgl Dikembalikan</th>
                <th>Foto Bukti</th>
                <th>Kondisi</th>
                <th>Status</th>
                <th>Denda</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjamans as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <div style="color: var(--text-primary); font-weight: 500;">{{ $item->user->name ?? '-' }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $item->user->kelas ?? '' }}</div>
                </td>
                <td>{{ $item->alat->nama_alat ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                <td>
                    @if($item->tanggal_kembali)
                        <span style="color: {{ \Carbon\Carbon::parse($item->tanggal_kembali)->isPast() ? '#f87171' : 'var(--text-primary)' }}">
                            {{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') }}
                        </span>
                    @else
                        <span style="color: var(--text-muted)">-</span>
                    @endif
                </td>
                <td>{{ $item->tanggal_dikembalikan ? \Carbon\Carbon::parse($item->tanggal_dikembalikan)->format('d M Y') : '-' }}</td>
                <td>
                    @if($item->foto_bukti)
                        <a href="{{ asset('storage/' . $item->foto_bukti) }}" target="_blank">
                            <img src="{{ asset('storage/' . $item->foto_bukti) }}"
                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px; cursor: pointer;">
                        </a>
                    @else
                        <span style="color: var(--text-muted); font-size: 0.78rem;">-</span>
                    @endif
                </td>
                <td style="font-size: 0.78rem; max-width: 150px;">{{ $item->keterangan ?? '-' }}</td>
                <td>
                    @if($item->status == 'menunggu_verifikasi')
                        <span class="status-badge badge-yellow">Menunggu Verifikasi</span>
                    @elseif($item->status == 'perlu_bayar_denda')
                        <span class="status-badge badge-red">Perlu Bayar Denda</span>
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
                    @if($item->status == 'menunggu_verifikasi')
                        <form action="{{ route('petugas.pengembalian.verifikasi', $item) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm"
                                    style="background: rgba(16,185,129,0.15); color: #34d399; border-radius: 6px; font-size: 0.75rem;">
                                ✅ Verifikasi
                            </button>
                        </form>
                    @elseif($item->status == 'perlu_bayar_denda')
                        <form action="{{ route('petugas.pengembalian.konfirmasi-denda', $item) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm"
                                    style="background: rgba(245,158,11,0.15); color: #fbbf24; border-radius: 6px; font-size: 0.75rem;">
                                💰 Konfirmasi Lunas
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center py-4" style="color: var(--text-muted)">
                    Tidak ada pengembalian yang perlu diverifikasi
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection