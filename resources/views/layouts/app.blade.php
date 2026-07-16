<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('QR Attendance')) — QR Attendance</title>
    <meta name="description" content="QR Code Attendance Management System">

    {{-- Bootstrap 5 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #0f1117;
            --sidebar-accent: #6c63ff;
            --sidebar-text: #a9b3cc;
            --sidebar-hover: rgba(108,99,255,0.12);
            --entry-color: #10b981;
            --exit-color: #ef4444;
            --reject-color: #f59e0b;
            --card-radius: 16px;
        }

        .fw-600 { font-weight: 600; }
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f7;
            color: #1a1f36;
            min-height: 100vh;
        }

        /* ── Sidebar ─────────────────────────────────────────── */
        #sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            padding: 0;
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        .sidebar-brand {
            padding: 28px 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .sidebar-brand h5 {
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
            margin: 0;
            letter-spacing: -0.3px;
        }
        .sidebar-brand span {
            font-size: 0.72rem;
            color: var(--sidebar-accent);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .sidebar-brand .brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--sidebar-accent), #a78bfa);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            color: #fff;
            margin-bottom: 10px;
        }
        .sidebar-nav { padding: 16px 0; flex: 1; overflow-y: auto; }
        .nav-section-label {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.25);
            padding: 16px 24px 6px;
        }
        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 24px;
            color: var(--sidebar-text);
            font-size: 0.88rem;
            font-weight: 500;
            border-radius: 0;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            text-decoration: none;
        }
        .sidebar-nav .nav-link:hover {
            color: #fff;
            background: var(--sidebar-hover);
        }
        .sidebar-nav .nav-link.active {
            color: #fff;
            background: var(--sidebar-hover);
            border-left-color: var(--sidebar-accent);
        }
        .sidebar-nav .nav-link i { font-size: 1.05rem; width: 20px; text-align: center; }
        .sidebar-footer {
            padding: 16px 24px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }
        .admin-badge {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px;
            background: rgba(255,255,255,0.05);
            border-radius: 10px;
            margin-bottom: 12px;
        }
        .admin-badge .avatar {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, var(--sidebar-accent), #a78bfa);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: 0.85rem;
        }
        .admin-badge .admin-info small { color: rgba(255,255,255,0.4); font-size: 0.7rem; }
        .admin-badge .admin-name { color: #fff; font-size: 0.82rem; font-weight: 600; }

        /* ── Main Content ─────────────────────────────────────── */
        #main-content {
            margin-left: {{ app()->getLocale() == 'ar' ? '0' : 'var(--sidebar-width)' }};
            margin-right: {{ app()->getLocale() == 'ar' ? 'var(--sidebar-width)' : '0' }};
            min-height: 100vh;
            transition: margin-left 0.3s ease, margin-right 0.3s ease;
        }
        [dir="rtl"] #sidebar { right: 0; left: auto; }
        [dir="rtl"] .sidebar-nav .nav-link { border-left: none; border-right: 3px solid transparent; }
        [dir="rtl"] .sidebar-nav .nav-link.active { border-right-color: var(--sidebar-accent); }
        [dir="rtl"] .me-1 { margin-right: 0 !important; margin-left: 0.25rem !important; }
        [dir="rtl"] .border-start-0 { border-right: 0 !important; border-left: 1.5px solid #e8ecf0 !important; }

        .topbar {
            background: #fff;
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e8ecf0;
            position: sticky; top: 0; z-index: 100;
        }
        .topbar-title { font-size: 1.1rem; font-weight: 700; color: #1a1f36; }
        .page-content { padding: 28px; }

        /* ── Cards ────────────────────────────────────────────── */
        .stat-card {
            background: #fff;
            border-radius: var(--card-radius);
            padding: 24px;
            position: relative;
            overflow: hidden;
            border: 1px solid #e8ecf0;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
        .stat-card .stat-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 16px;
        }
        .stat-card .stat-value { font-size: 2.2rem; font-weight: 800; line-height: 1; color: #1a1f36; }
        .stat-card .stat-label { font-size: 0.82rem; color: #6b7280; font-weight: 500; margin-top: 4px; }
        .stat-card::after {
            content: '';
            position: absolute;
            top: -30px; right: -30px;
            width: 100px; height: 100px;
            border-radius: 50%;
            opacity: 0.06;
        }
        .stat-inside .stat-icon { background: rgba(16,185,129,0.1); color: var(--entry-color); }
        .stat-inside::after { background: var(--entry-color); }
        .stat-entry .stat-icon { background: rgba(108,99,255,0.1); color: var(--sidebar-accent); }
        .stat-entry::after { background: var(--sidebar-accent); }
        .stat-exit .stat-icon { background: rgba(239,68,68,0.1); color: var(--exit-color); }
        .stat-exit::after { background: var(--exit-color); }

        .content-card {
            background: #fff;
            border-radius: var(--card-radius);
            border: 1px solid #e8ecf0;
            overflow: hidden;
        }
        .content-card .card-header {
            background: transparent;
            border-bottom: 1px solid #f0f2f7;
            padding: 18px 24px;
            font-weight: 700;
            font-size: 0.92rem;
            color: #1a1f36;
        }
        .content-card .card-body { padding: 0; }

        /* ── Tables ───────────────────────────────────────────── */
        .table { margin: 0; font-size: 0.875rem; }
        .table thead th {
            background: #f8fafc;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #6b7280;
            border-bottom: 1px solid #e8ecf0;
            padding: 12px 16px;
        }
        .table tbody td { padding: 13px 16px; vertical-align: middle; border-color: #f0f2f7; }
        .table tbody tr:hover { background: #fafbfd; }

        /* ── Badges ───────────────────────────────────────────── */
        .badge-entry {
            background: rgba(16,185,129,0.1);
            color: var(--entry-color);
            font-weight: 600;
            font-size: 0.72rem;
            padding: 5px 10px;
            border-radius: 6px;
        }
        .badge-exit {
            background: rgba(239,68,68,0.1);
            color: var(--exit-color);
            font-weight: 600;
            font-size: 0.72rem;
            padding: 5px 10px;
            border-radius: 6px;
        }
        .badge-rejected {
            background: rgba(245,158,11,0.1);
            color: var(--reject-color);
            font-weight: 600;
            font-size: 0.72rem;
            padding: 5px 10px;
            border-radius: 6px;
        }
        .badge-inside {
            background: rgba(16,185,129,0.1);
            color: var(--entry-color);
            font-size: 0.72rem;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 600;
        }
        .badge-outside {
            background: rgba(107,114,128,0.1);
            color: #6b7280;
            font-size: 0.72rem;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 600;
        }

        /* ── Buttons ──────────────────────────────────────────── */
        .btn-primary-custom {
            background: linear-gradient(135deg, #6c63ff, #a78bfa);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }
        .btn-primary-custom:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(108,99,255,0.35); color: #fff; }
        .btn-icon { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; }

        /* ── Forms ────────────────────────────────────────────── */
        .form-control, .form-select {
            border: 1.5px solid #e8ecf0;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.875rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--sidebar-accent);
            box-shadow: 0 0 0 3px rgba(108,99,255,0.12);
        }
        .form-label { font-weight: 600; font-size: 0.82rem; color: #374151; margin-bottom: 6px; }

        /* ── Alerts ───────────────────────────────────────────── */
        .alert { border-radius: 12px; border: none; font-size: 0.875rem; font-weight: 500; }
        .alert-success { background: rgba(16,185,129,0.1); color: #065f46; }
        .alert-danger  { background: rgba(239,68,68,0.1); color: #7f1d1d; }

        /* ── Mobile ───────────────────────────────────────────── */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.4rem;
            color: #1a1f36;
            cursor: pointer;
        }
        @media (max-width: 991.98px) {
            #sidebar { transform: translateX(-100%); }
            [dir="rtl"] #sidebar { transform: translateX(100%); }
            #sidebar.show { transform: translateX(0); }
            #main-content { margin-left: 0 !important; margin-right: 0 !important; }
            .sidebar-toggle { display: flex; }
            .page-content { padding: 16px; }
            .topbar { padding: 12px 16px; }
        }
        @media (max-width: 575.98px) {
            #live-time { display: none; }
            .btn-group.me-3 { margin-right: 0.5rem !important; }
        }
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        .sidebar-overlay.show { display: block; }
    </style>
    @stack('styles')
</head>
<body>

{{-- Sidebar Overlay (mobile) --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- Sidebar --}}
<nav id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="bi bi-qr-code-scan"></i>
        </div>
        <h5>QR Attendance</h5>
        <span>{{ __('Management System') }}</span>
    </div>

    <div class="sidebar-nav">
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> {{ __('Dashboard') }}
        </a>

        <div class="nav-section-label">{{ __('Scanners') }}</div>
        <a href="{{ route('scanner.entry') }}" class="nav-link {{ request()->routeIs('scanner.entry') ? 'active' : '' }}">
            <i class="bi bi-box-arrow-in-right"></i> {{ __('Scanner Entry') }}
        </a>
        <a href="{{ route('scanner.exit') }}" class="nav-link {{ request()->routeIs('scanner.exit') ? 'active' : '' }}">
            <i class="bi bi-box-arrow-right"></i> {{ __('Scanner Exit') }}
        </a>

        <div class="nav-section-label">{{ __('People') }}</div>
        <a href="{{ route('persons.index') }}" class="nav-link {{ request()->routeIs('persons.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> {{ __('Manage People') }}
        </a>

        <div class="nav-section-label">{{ __('Attendance') }}</div>
        <a href="{{ route('attendance.inside') }}" class="nav-link {{ request()->routeIs('attendance.inside') ? 'active' : '' }}">
            <i class="bi bi-door-open-fill"></i> {{ __('Currently Inside') }}
        </a>
        <a href="{{ route('attendance.history') }}" class="nav-link {{ request()->routeIs('attendance.history') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i> {{ __('Attendance History') }}
        </a>
        <a href="{{ route('attendance.rejected') }}" class="nav-link {{ request()->routeIs('attendance.rejected') ? 'active' : '' }}">
            <i class="bi bi-x-octagon-fill"></i> {{ __('Rejected Scans') }}
        </a>
    </div>

    <div class="sidebar-footer">
        <div class="admin-badge">
            <div class="avatar">A</div>
            <div class="admin-info">
                <div class="admin-name">{{ __('Administrator') }}</div>
                <small>{{ auth('admin')->user()->email ?? '' }}</small>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger w-100 rounded-3">
                <i class="bi bi-box-arrow-left me-1"></i> {{ __('Logout') }}
            </button>
        </form>
    </div>
</nav>

{{-- Main Content --}}
<div id="main-content">
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="sidebar-toggle" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="btn-group me-3">
                <a href="{{ route('lang.switch', 'fr') }}" class="btn btn-sm btn-{{ app()->getLocale() == 'fr' ? 'primary' : 'light' }} border">FR</a>
                <a href="{{ route('lang.switch', 'ar') }}" class="btn btn-sm btn-{{ app()->getLocale() == 'ar' ? 'primary' : 'light' }} border">عربي</a>
            </div>
            <span class="text-muted" style="font-size:0.8rem;">
                <i class="bi bi-clock me-1"></i>
                <span id="live-time"></span>
            </span>
        </div>
    </div>

    <div class="page-content">
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Live clock
    function updateTime() {
        const now = new Date();
        document.getElementById('live-time').textContent = now.toLocaleTimeString('en-GB');
    }
    setInterval(updateTime, 1000);
    updateTime();

    // Sidebar toggle (mobile)
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('show');
        document.getElementById('sidebarOverlay').classList.toggle('show');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('show');
        document.getElementById('sidebarOverlay').classList.remove('show');
    }

    // Auto-dismiss alerts
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(a => {
            const alert = bootstrap.Alert.getOrCreateInstance(a);
            if (alert) alert.close();
        });
    }, 4000);
</script>
@stack('scripts')
</body>
</html>
