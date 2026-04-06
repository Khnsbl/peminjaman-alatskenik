@extends('layouts.user')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stats Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(99,102,241,0.2); font-size: 1.2rem;">📋</div>
            <div class="stat-number">{{ $totalPeminjaman ?? 0 }}</div>
            <div class="stat-label">Total Peminjaman</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(245,158,11,0.2); font-size: 1.2rem;">⏳</div>
            <div class="stat-number">{{ $menunggu ?? 0 }}</div>
            <div class="stat-label">Menunggu Validasi</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(59,130,246,0.2); font-size: 1.2rem;">🔧</div>
            <div class="stat-number">{{ $dipinjam ?? 0 }}</div>
            <div class="stat-label">Sedang Dipinjam</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(16,185,129,0.2); font-size: 1.2rem;">✅</div>
            <div class="stat-number">{{ $dikembalikan ?? 0 }}</div>
            <div class="stat-label">Dikembalikan</div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <a href="{{ route('user.alat.index') }}" class="dark-card d-block text-decoration-none" style="transition: transform 0.15s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 48px; height: 48px; background: rgba(99,102,241,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">🔧</div>
                <div>
                    <div style="font-weight: 600; color: var(--text-primary);">Daftar Alat</div>
                    <div style="font-size: 0.78rem; color: var(--text-muted);">Lihat alat yang tersedia</div>
                </div>
                <i class="bi bi-chevron-right ms-auto" style="color: var(--text-muted);"></i>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('user.peminjaman.create') }}" class="dark-card d-block text-decoration-none" style="transition: transform 0.15s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 48px; height: 48px; background: rgba(16,185,129,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">📝</div>
                <div>
                    <div style="font-weight: 600; color: var(--text-primary);">Ajukan Peminjaman</div>
                    <div style="font-size: 0.78rem; color: var(--text-muted);">Buat permintaan baru</div>
                </div>
                <i class="bi bi-chevron-right ms-auto" style="color: var(--text-muted);"></i>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('user.peminjaman.index') }}" class="dark-card d-block text-decoration-none" style="transition: transform 0.15s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 48px; height: 48px; background: rgba(245,158,11,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">📋</div>
                <div>
                    <div style="font-weight: 600; color: var(--text-primary);">Peminjaman Saya</div>
                    <div style="font-size: 0.78rem; color: var(--text-muted);">Riwayat peminjaman</div>
                </div>
                <i class="bi bi-chevron-right ms-auto" style="color: var(--text-muted);"></i>
            </div>
        </a>
    </div>
</div>

{{-- Riwayat Peminjaman Terbaru --}}
<div class="dark-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-semibold mb-0" style="color: var(--text-primary);">Peminjaman Terbaru</h6>
        <a href="{{ route('user.peminjaman.index') }}" style="font-size: 0.82rem; color: var(--accent-hover); text-decoration: none;">Lihat Semua →</a>
    </div>
    <table class="table dark-table">
        <thead>
            <tr>
                <th>Alat</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentPeminjaman ?? [] as $item)
            <tr>
                <td style="color: var(--text-primary)">{{ $item->alat->nama_alat ?? '-' }}</td>
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
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-4" style="color: var(--text-muted);">Belum ada peminjaman</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection