<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Peminjaman Alat</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-bg: #0f1117;
            --sidebar-width: 240px;
            --main-bg: #13161e;
            --card-bg: #1a1d27;
            --card-border: rgba(255,255,255,0.07);
            --text-primary: #f0f2f8;
            --text-secondary: #9ca3b0;
            --text-muted: #5c6070;
            --accent: #6366f1;
            --accent-hover: #818cf8;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--main-bg);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: row;
            overflow-x: hidden;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            min-width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 100;
            border-right: 1px solid var(--card-border);
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid var(--card-border);
            margin-bottom: 0.5rem;
            flex-shrink: 0;
        }

        .brand-icon {
            width: 38px; height: 38px;
            background: var(--accent);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .brand-icon i { color: #fff; font-size: 1rem; }
        .brand-title { font-size: 0.95rem; font-weight: 700; color: var(--text-primary); line-height: 1.2; }
        .brand-sub { font-size: 0.68rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; }

        .nav-section-label {
            font-size: 0.65rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 0.75rem 1.25rem 0.25rem;
        }

        .nav-link-item {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.6rem 1.25rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 8px;
            margin: 0.1rem 0.75rem;
            transition: all 0.15s;
        }

        .nav-link-item:hover { background: rgba(255,255,255,0.06); color: var(--text-primary); }
        .nav-link-item.active { background: var(--accent); color: #fff; }
        .nav-link-item i { font-size: 1rem; width: 18px; text-align: center; flex-shrink: 0; }

        .nav-badge {
            margin-left: auto;
            background: #ef4444;
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 20px;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 1rem 0.75rem;
            border-top: 1px solid var(--card-border);
            flex-shrink: 0;
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.5rem 0.5rem;
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }

        .user-avatar {
            width: 32px; height: 32px;
            background: #7c3aed;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .user-name { font-size: 0.82rem; font-weight: 600; color: var(--text-primary); }
        .user-role { font-size: 0.7rem; color: var(--text-muted); }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.5rem 0.75rem;
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: 8px;
            color: #f87171;
            font-size: 0.82rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s;
        }

        .btn-logout:hover { background: rgba(239,68,68,0.2); color: #fca5a5; }

        /* ===== MAIN WRAPPER ===== */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            flex: 1;
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            padding: 1.75rem;
            overflow-x: hidden;
        }

        /* ===== TOP NAVBAR ===== */
        .top-navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.75rem;
            width: 100%;
        }

        .top-navbar-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
            line-height: 1.3;
        }

        .top-navbar-date {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .top-navbar-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-shrink: 0;
        }

        .btn-icon {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 8px;
            width: 36px; height: 36px;
            display: flex; align-items: center; justify-content: center;
            padding: 0;
            cursor: pointer;
            transition: all 0.15s;
        }

        .btn-icon:hover { border-color: rgba(255,255,255,0.15); }

        .avatar-circle {
            width: 34px; height: 34px;
            background: #7c3aed;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        /* ===== CARDS ===== */
        .dark-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 12px;
            padding: 1.25rem;
        }

        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 12px;
            padding: 1.25rem;
            transition: transform 0.15s;
            height: 100%;
        }

        .stat-card:hover { transform: translateY(-2px); border-color: rgba(255,255,255,0.12); }

        .stat-icon {
            width: 42px; height: 42px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem;
            margin-bottom: 0.9rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
            letter-spacing: -0.04em;
        }

        .stat-label { font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem; }

        /* ===== TABLE ===== */
        .dark-table {
            --bs-table-bg: transparent;
            --bs-table-color: var(--text-secondary);
            --bs-table-border-color: var(--card-border);
            margin-bottom: 0;
            font-size: 0.85rem;
        }

        .dark-table thead th {
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted);
            border-bottom: 1px solid var(--card-border);
            padding: 0.5rem 0.75rem;
        }

        .dark-table tbody td {
            border-color: var(--card-border);
            padding: 0.75rem 0.75rem;
            vertical-align: middle;
        }

        .dark-table tbody tr:hover { background: rgba(255,255,255,0.03); }

        /* ===== BADGES ===== */
        .status-badge {
            font-size: 0.72rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .badge-blue   { background: rgba(59,130,246,0.15); color: #60a5fa; }
        .badge-green  { background: rgba(16,185,129,0.15); color: #34d399; }
        .badge-yellow { background: rgba(245,158,11,0.15); color: #fbbf24; }
        .badge-red    { background: rgba(239,68,68,0.15);  color: #f87171; }
        .badge-gray   { background: rgba(255,255,255,0.08); color: var(--text-muted); }

        /* ===== QUICK LINKS ===== */
        .quick-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 0.75rem;
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--card-border);
            border-radius: 8px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.15s;
        }

        .quick-link:hover {
            background: rgba(255,255,255,0.07);
            color: var(--text-primary);
            border-color: rgba(255,255,255,0.12);
        }

        .quick-link-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .btn-link-custom {
            font-size: 0.8rem;
            color: var(--accent-hover);
            text-decoration: none;
            font-weight: 500;
        }

        .btn-link-custom:hover { color: #fff; }

        .progress-track {
            height: 5px;
            background: rgba(255,255,255,0.06);
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 10px;
            transition: width 0.6s ease;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main-wrapper {
                margin-left: 0;
                width: 100%;
                padding: 1rem;
            }
        }
    </style>
</head>
<body>

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon"><i class="bi bi-lightning-charge-fill"></i></div>
            <div>
                <div class="brand-title">SiPeminjam</div>
                <div class="brand-sub">Sistem Peminjaman Alat</div>
            </div>
        </div>

        <div class="nav-section-label">Menu Utama</div>

        <a href="{{ route('admin.dashboard') }}" class="nav-link-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
        <a href="{{ route('admin.peminjaman.index') }}" class="nav-link-item {{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}">
            <i class="bi bi-clipboard-list"></i> Peminjaman
            @if(isset($pendingCount) && $pendingCount > 0)
                <span class="nav-badge">{{ $pendingCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.alat.index') }}" class="nav-link-item {{ request()->routeIs('admin.alat.*') ? 'active' : '' }}">
            <i class="bi bi-tools"></i> Data Alat
        </a>
        <a href="{{ route('admin.akun.index') }}" class="nav-link-item {{ request()->routeIs('admin.akun.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Data User
        </a>

        <div class="nav-section-label">Lainnya</div>

        <a href="{{ route('admin.kategori.index') }}" class="nav-link-item {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
            <i class="bi bi-tag"></i> Kategori
        </a>
        <a href="{{ route('admin.log.index') }}" class="nav-link-item {{ request()->routeIs('admin.log.*') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i> Log Aktivitas
        </a>

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</div>
                <div>
                    <div class="user-name">{{ Auth::user()->name ?? 'Admin' }}</div>
                    <div class="user-role">{{ ucfirst(Auth::user()->role ?? 'admin') }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-left"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="main-wrapper">

        {{-- TOP NAVBAR --}}
        <div class="top-navbar">
            <div>
                <p class="top-navbar-title">@yield('page-title', 'Dashboard')</p>
                <p class="top-navbar-date">
                    {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                </p>
            </div>
            <div class="top-navbar-actions">
                <button class="btn-icon">
                    <i class="bi bi-bell" style="color: var(--text-secondary); font-size: 0.95rem;"></i>
                </button>
                <div class="avatar-circle">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-dismissible fade show mb-3" role="alert"
                 style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3); color: #34d399; font-size: 0.85rem;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" style="filter: invert(1);"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-dismissible fade show mb-3" role="alert"
                 style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #f87171; font-size: 0.85rem;">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" style="filter: invert(1);"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>