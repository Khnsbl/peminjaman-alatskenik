@extends('layouts.admin')
@section('title', 'Log Aktivitas')
@section('page-title', 'Log Aktivitas')

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(99,102,241,0.2);">
                <i class="bi bi-journal-text" style="color: #818cf8; font-size: 1.1rem;"></i>
            </div>
            <div class="stat-number">{{ $logs->count() }}</div>
            <div class="stat-label">Total Aktivitas</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(239,68,68,0.2);">
                <i class="bi bi-shield-fill" style="color: #f87171; font-size: 1.1rem;"></i>
            </div>
            <div class="stat-number">{{ $logs->filter(fn($l) => $l->user?->role === 'admin')->count() }}</div>
            <div class="stat-label">Aktivitas Admin</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(14,165,233,0.2);">
                <i class="bi bi-person-badge-fill" style="color: #38bdf8; font-size: 1.1rem;"></i>
            </div>
            <div class="stat-number">{{ $logs->filter(fn($l) => $l->user?->role === 'petugas')->count() }}</div>
            <div class="stat-label">Aktivitas Petugas</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(16,185,129,0.2);">
                <i class="bi bi-person-fill" style="color: #34d399; font-size: 1.1rem;"></i>
            </div>
            <div class="stat-number">{{ $logs->filter(fn($l) => $l->user?->role === 'user')->count() }}</div>
            <div class="stat-label">Aktivitas User</div>
        </div>
    </div>
</div>

{{-- Filter & Log --}}
<div class="dark-card">

    {{-- Header + Filter --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h6 class="fw-semibold mb-0" style="color: var(--text-primary);">Riwayat Aktivitas</h6>
        <div class="d-flex gap-2 flex-wrap">
            <button onclick="filterLog('all')" id="btn-all"
                    class="btn btn-sm px-3 filter-btn filter-btn-all active-filter"
                    style="border-radius: 8px; font-size: 0.78rem;">
                Semua
            </button>
            <button onclick="filterLog('admin')" id="btn-admin"
                    class="btn btn-sm px-3 filter-btn filter-btn-admin"
                    style="background: rgba(239,68,68,0.1); color: #f87171; border: 1px solid rgba(239,68,68,0.2); border-radius: 8px; font-size: 0.78rem;">
                Admin
            </button>
            <button onclick="filterLog('petugas')" id="btn-petugas"
                    class="btn btn-sm px-3 filter-btn filter-btn-petugas"
                    style="background: rgba(14,165,233,0.1); color: #38bdf8; border: 1px solid rgba(14,165,233,0.2); border-radius: 8px; font-size: 0.78rem;">
                Petugas
            </button>
            <button onclick="filterLog('user')" id="btn-user"
                    class="btn btn-sm px-3 filter-btn filter-btn-user"
                    style="background: rgba(16,185,129,0.1); color: #34d399; border: 1px solid rgba(16,185,129,0.2); border-radius: 8px; font-size: 0.78rem;">
                User
            </button>
        </div>
    </div>

    {{-- Log List --}}
    <div id="logContainer">
        @forelse($logs as $log)
        @php
            $role = $log->user?->role ?? 'unknown';
            $roleConfig = match($role) {
                'admin'   => ['bg' => 'rgba(239,68,68,0.15)',  'color' => '#f87171', 'label' => 'Admin',   'icon' => 'bi-shield-fill'],
                'petugas' => ['bg' => 'rgba(14,165,233,0.15)', 'color' => '#38bdf8', 'label' => 'Petugas', 'icon' => 'bi-person-badge-fill'],
                'user'    => ['bg' => 'rgba(16,185,129,0.15)', 'color' => '#34d399', 'label' => 'User',    'icon' => 'bi-person-fill'],
                default   => ['bg' => 'rgba(255,255,255,0.05)','color' => '#9ca3b0', 'label' => '?',       'icon' => 'bi-question-circle'],
            };

            $actColor = '#9ca3b0';
            $actIcon  = 'bi-activity';
            $lowerAct = strtolower($log->aktivitas);
            if (str_contains($lowerAct, 'menambah'))        { $actColor = '#34d399'; $actIcon = 'bi-plus-circle-fill'; }
            elseif (str_contains($lowerAct, 'mengubah'))    { $actColor = '#fbbf24'; $actIcon = 'bi-pencil-fill'; }
            elseif (str_contains($lowerAct, 'menghapus'))   { $actColor = '#f87171'; $actIcon = 'bi-trash-fill'; }
            elseif (str_contains($lowerAct, 'menyetujui'))  { $actColor = '#34d399'; $actIcon = 'bi-check-circle-fill'; }
            elseif (str_contains($lowerAct, 'menolak'))     { $actColor = '#f87171'; $actIcon = 'bi-x-circle-fill'; }
            elseif (str_contains($lowerAct, 'memverifikasi')){ $actColor = '#60a5fa'; $actIcon = 'bi-patch-check-fill'; }
        @endphp

        <div class="log-item d-flex align-items-start gap-3 mb-3 pb-3"
             style="border-bottom: 1px solid var(--card-border);"
             data-role="{{ $role }}">

            {{-- Role Avatar Icon --}}
            <div class="flex-shrink-0"
                 style="width: 40px; height: 40px; background: {{ $roleConfig['bg'] }}; border-radius: 10px;
                        display: flex; align-items: center; justify-content: center;">
                <i class="bi {{ $roleConfig['icon'] }}" style="color: {{ $roleConfig['color'] }}; font-size: 1rem;"></i>
            </div>

            {{-- Content --}}
            <div class="flex-grow-1" style="min-width: 0;">

                {{-- Row 1: Name + Badge + Timestamp --}}
                <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap mb-1">
                    <div class="d-flex align-items-center gap-2">
                        <span style="font-weight: 600; color: var(--text-primary); font-size: 0.88rem; line-height: 1;">
                            {{ $log->user?->name ?? 'Unknown' }}
                        </span>
                        <span style="font-size: 0.7rem; font-weight: 600; padding: 2px 8px; border-radius: 20px;
                                     background: {{ $roleConfig['bg'] }}; color: {{ $roleConfig['color'] }}; line-height: 1.4; white-space: nowrap;">
                            {{ $roleConfig['label'] }}
                        </span>
                    </div>
                    <span style="font-size: 0.72rem; color: var(--text-muted); white-space: nowrap; flex-shrink: 0;">
                        {{ $log->created_at->format('d M Y, H:i') }}
                    </span>
                </div>

                {{-- Row 2: Activity --}}
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="bi {{ $actIcon }} flex-shrink-0" style="color: {{ $actColor }}; font-size: 0.85rem;"></i>
                    <span style="font-size: 0.85rem; color: {{ $actColor }}; word-break: break-word;">
                        {{ $log->aktivitas }}
                    </span>
                </div>

                {{-- Row 3: Meta Info --}}
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    @if($log->model)
                    <span style="font-size: 0.72rem; color: var(--text-muted);">
                        <i class="bi bi-folder2 me-1"></i>{{ $log->model }}@if($log->model_id) #{{ $log->model_id }}@endif
                    </span>
                    @endif
                    <span style="font-size: 0.72rem; color: var(--text-muted);">
                        <i class="bi bi-clock me-1"></i>{{ $log->created_at->diffForHumans() }}
                    </span>
                </div>

            </div>
        </div>

        @empty
        <div class="text-center py-5" style="color: var(--text-muted);">
            <i class="bi bi-inbox" style="font-size: 2.5rem;"></i>
            <div class="mt-2" style="font-size: 0.88rem;">Belum ada aktivitas tercatat</div>
        </div>
        @endforelse
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const buttons = {
        all:     document.getElementById('btn-all'),
        admin:   document.getElementById('btn-admin'),
        petugas: document.getElementById('btn-petugas'),
        user:    document.getElementById('btn-user'),
    };

    const defaultStyles = {
        all:     { bg: 'rgba(255,255,255,0.05)', color: 'var(--text-secondary)', border: '1px solid var(--card-border)' },
        admin:   { bg: 'rgba(239,68,68,0.1)',    color: '#f87171',               border: '1px solid rgba(239,68,68,0.2)' },
        petugas: { bg: 'rgba(14,165,233,0.1)',   color: '#38bdf8',               border: '1px solid rgba(14,165,233,0.2)' },
        user:    { bg: 'rgba(16,185,129,0.1)',   color: '#34d399',               border: '1px solid rgba(16,185,129,0.2)' },
    };

    function resetButtons() {
        Object.entries(buttons).forEach(([key, btn]) => {
            const s = defaultStyles[key];
            btn.style.background = s.bg;
            btn.style.color      = s.color;
            btn.style.border     = s.border;
        });
    }

    function setActive(btn) {
        btn.style.background = 'var(--accent)';
        btn.style.color      = '#fff';
        btn.style.border     = 'none';
    }

    function filterLog(role) {
        document.querySelectorAll('.log-item').forEach(item => {
            item.style.display = (role === 'all' || item.dataset.role === role) ? 'flex' : 'none';
        });
        resetButtons();
        if (buttons[role]) setActive(buttons[role]);
    }

    Object.entries(buttons).forEach(([key, btn]) => {
        btn.addEventListener('click', () => filterLog(key));
    });

    // Default: tampilkan semua
    filterLog('all');
});
</script>
@endpush