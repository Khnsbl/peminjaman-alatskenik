@extends('layouts.admin')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stats Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(99, 102, 241, 0.2);">
                <i class="bi bi-clipboard-list" style="color: #818cf8;"></i>
            </div>
            <div class="stat-number">{{ $totalPeminjaman ?? 0 }}</div>
            <div class="stat-label">Total Peminjaman</div>
            <div class="stat-sub text-success">
                <i class="bi bi-arrow-up-short"></i> Semua transaksi
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.2);">
                <i class="bi bi-check2-circle" style="color: #34d399;"></i>
            </div>
            <div class="stat-number">{{ $sedangDipinjam ?? 0 }}</div>
            <div class="stat-label">Sedang Dipinjam</div>
            <div class="stat-sub text-success">
                <i class="bi bi-arrow-up-short"></i> Aktif sekarang
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(245, 158, 11, 0.2);">
                <i class="bi bi-hourglass-split" style="color: #fbbf24;"></i>
            </div>
            <div class="stat-number">{{ $menungguKonfirmasi ?? 0 }}</div>
            <div class="stat-label">Menunggu Konfirmasi</div>
            <div class="stat-sub text-danger">
                <i class="bi bi-arrow-down-short"></i> Perlu tindakan
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(59, 130, 246, 0.2);">
                <i class="bi bi-box-seam" style="color: #60a5fa;"></i>
            </div>
            <div class="stat-number">{{ $totalAlat ?? 0 }}</div>
            <div class="stat-label">Total Alat Tersedia</div>
            <div class="stat-sub text-success">
                <i class="bi bi-arrow-up-short"></i> 100% tersedia
            </div>
        </div>
    </div>
</div>

{{-- Main Content Row --}}
<div class="row g-3">

    {{-- Recent Peminjaman Table --}}
    <div class="col-lg-7">
        <div class="dark-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-semibold mb-0" style="color: var(--text-primary);">Peminjaman Terbaru</h6>
                <a href="{{ route('admin.peminjaman.index') }}" class="btn-link-custom">Lihat Semua &rarr;</a>
            </div>
            <div class="table-responsive">
                <table class="table dark-table">
                    <thead>
                        <tr>
                            <th>Peminjam</th>
                            <th>Alat</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPeminjaman ?? [] as $item)
                        <tr>
                            <td>
                                <div style="font-weight: 500; color: var(--text-primary); font-size: 0.88rem;">{{ $item->user->name ?? '-' }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $item->user->email ?? '' }}</div>
                            </td>
                            <td style="font-size: 0.88rem;">{{ $item->alat->nama_alat ?? '-' }}</td>
                            <td style="font-size: 0.85rem;">{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                            <td>
                                @php
                                    $statusMap = [
                                        'dipinjam'    => ['label' => 'Dipinjam',    'class' => 'badge-blue'],
                                        'dikembalikan'=> ['label' => 'Dikembalikan','class' => 'badge-green'],
                                        'menunggu'    => ['label' => 'Menunggu',    'class' => 'badge-yellow'],
                                        'ditolak'     => ['label' => 'Ditolak',     'class' => 'badge-red'],
                                    ];
                                    $s = $statusMap[$item->status] ?? ['label' => ucfirst($item->status), 'class' => 'badge-gray'];
                                @endphp
                                <span class="status-badge {{ $s['class'] }}">{{ $s['label'] }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4" style="color: var(--text-muted); font-size: 0.85rem;">
                                Belum ada data peminjaman
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Right Column --}}
    <div class="col-lg-5 d-flex flex-column gap-3">

        {{-- Status Alat --}}
        <div class="dark-card">
            <h6 class="fw-semibold mb-3" style="color: var(--text-primary);">Status Alat</h6>
            @php
                $statusAlat = [
                    ['label' => 'Tersedia',       'value' => $alatTersedia ?? 0,     'color' => '#34d399', 'max' => max($totalAlat ?? 1, 1)],
                    ['label' => 'Dipinjam',       'value' => $sedangDipinjam ?? 0,   'color' => '#60a5fa', 'max' => max($totalAlat ?? 1, 1)],
                    ['label' => 'Dalam Perbaikan','value' => $alatPerbaikan ?? 0,    'color' => '#fbbf24', 'max' => max($totalAlat ?? 1, 1)],
                    ['label' => 'Tidak Tersedia', 'value' => $alatTidakTersedia ?? 0,'color' => '#f87171', 'max' => max($totalAlat ?? 1, 1)],
                ];
            @endphp
            @foreach($statusAlat as $s)
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span style="font-size: 0.82rem; color: var(--text-secondary);">{{ $s['label'] }}</span>
                    <span style="font-size: 0.82rem; font-weight: 600; color: var(--text-primary);">{{ $s['value'] }}</span>
                </div>
                <div class="progress-track">
                    <div class="progress-fill" style="width: {{ ($s['value']/$s['max'])*100 }}%; background: {{ $s['color'] }};"></div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Akses Cepat --}}
        <div class="dark-card">
            <h6 class="fw-semibold mb-3" style="color: var(--text-primary);">Akses Cepat</h6>
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('admin.akun.index') }}" class="quick-link">
                    <div class="quick-link-icon" style="background: rgba(99,102,241,0.2);">
                        <i class="bi bi-people" style="color: #818cf8; font-size: 0.9rem;"></i>
                    </div>
                    <span>Kelola Akun</span>
                    <i class="bi bi-chevron-right ms-auto" style="color: var(--text-muted); font-size: 0.75rem;"></i>
                </a>
                <a href="{{ route('admin.kategori.index') }}" class="quick-link">
                    <div class="quick-link-icon" style="background: rgba(245,158,11,0.2);">
                        <i class="bi bi-tag" style="color: #fbbf24; font-size: 0.9rem;"></i>
                    </div>
                    <span>Kategori</span>
                    <i class="bi bi-chevron-right ms-auto" style="color: var(--text-muted); font-size: 0.75rem;"></i>
                </a>
                <a href="{{ route('admin.alat.index') }}" class="quick-link">
                    <div class="quick-link-icon" style="background: rgba(16,185,129,0.2);">
                        <i class="bi bi-tools" style="color: #34d399; font-size: 0.9rem;"></i>
                    </div>
                    <span>Kelola Alat</span>
                    <i class="bi bi-chevron-right ms-auto" style="color: var(--text-muted); font-size: 0.75rem;"></i>
                </a>
                <a href="{{ route('admin.peminjaman.index') }}" class="quick-link">
                    <div class="quick-link-icon" style="background: rgba(59,130,246,0.2);">
                        <i class="bi bi-clipboard-check" style="color: #60a5fa; font-size: 0.9rem;"></i>
                    </div>
                    <span>Peminjaman</span>
                    <i class="bi bi-chevron-right ms-auto" style="color: var(--text-muted); font-size: 0.75rem;"></i>
                </a>
            </div>
        </div>

    </div>
</div>

{{-- Footer Status --}}
<div class="dark-card mt-3">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <div style="width: 8px; height: 8px; background: #34d399; border-radius: 50%; animation: pulse 2s infinite;"></div>
            <span style="font-size: 0.82rem; color: var(--text-muted);">Sistem berjalan normal</span>
        </div>
        <span style="font-size: 0.78rem; color: var(--text-muted);">{{ now()->translatedFormat('l, d F Y • H:i') }} WIB</span>
    </div>
</div>

@endsection