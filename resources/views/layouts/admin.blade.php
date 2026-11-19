<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Administrator — JagoBahasa')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="student-shell">

    <!-- SIDEBAR ADMINISTRATOR -->
    <aside class="student-sidebar d-flex flex-column" id="adminSidebar">
        <div class="sidebar-header d-flex align-items-center justify-content-between px-4 py-3 border-bottom">
            <a href="{{ url('/') }}" class="d-flex align-items-center gap-2 text-decoration-none text-brand">
                <span class="brand-icon d-inline-flex align-items-center justify-content-center rounded-3 text-white">
                    <i class="bi bi-translate"></i>
                </span>
                <div>
                    <span class="font-display fw-extrabold fs-5 d-block lh-1">JagoBahasa</span>
                    <small class="text-muted" style="font-size: 0.72rem;">Panel Administrator</small>
                </div>
            </a>
            <button class="btn btn-sm btn-light d-lg-none" id="closeSidebarBtn">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="sidebar-menu flex-grow-1 px-3 py-3 overflow-y-auto">
            <small class="text-uppercase text-muted fw-bold px-2 d-block mb-2" style="font-size: 0.7rem; letter-spacing: 0.05em;">
                Kontrol &amp; Monitoring
            </small>
            <ul class="nav flex-column gap-1">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="student-nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Ringkasan Sistem</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users') }}" class="student-nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i>
                        <span>Data Murid &amp; Guru</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.grades') }}" class="student-nav-link {{ request()->is('admin/grades*') ? 'active' : '' }}">
                        <i class="bi bi-award-fill"></i>
                        <span>Rekap Nilai Murid</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="p-3 border-top bg-light">
            <div class="d-flex align-items-center gap-2">
                <div class="avatar-circle bg-dark text-white fw-bold flex-shrink-0">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <div class="overflow-hidden flex-grow-1">
                    <div class="fw-bold small text-truncate">{{ Auth::user()->name ?? 'Administrator' }}</div>
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25" style="font-size: 0.7rem;">
                        Super Administrator
                    </span>
                </div>
            </div>
        </div>
    </aside>

    <div class="sidebar-backdrop d-lg-none" id="sidebarBackdrop"></div>

    <!-- KONTEN UTAMA -->
    <div class="student-main-wrapper d-flex flex-column min-vh-100">
        <header class="student-topbar sticky-top bg-white border-bottom px-3 px-lg-4 py-2">
            <div class="d-flex align-items-center justify-content-between" style="min-height: 52px;">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-light border d-lg-none" id="openSidebarBtn">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <div>
                        <h6 class="fw-bold mb-0 font-display">@yield('page_title', 'Panel Administrator')</h6>
                        <small class="text-muted d-none d-sm-inline">@yield('page_subtitle', 'Monitoring pengguna, kelas, dan capaian nilai peserta didik')</small>
                    </div>
                </div>

                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle bg-light border rounded-pill py-1 px-2 pe-3" data-bs-toggle="dropdown">
                        <div class="avatar-circle bg-dark text-white fw-bold me-2" style="width: 28px; height: 28px; font-size: 0.8rem;">A</div>
                        <span class="d-none d-md-inline text-dark fw-semibold small">{{ Auth::user()->name ?? 'Admin' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="flex-grow-1 p-3 p-lg-4">
            <div class="container-fluid px-0" style="max-width: 1280px;">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <footer class="bg-white border-top py-3 px-4 small text-muted">
            <div class="d-flex justify-content-between align-items-center">
                <span>&copy; {{ date('Y') }} <strong>JagoBahasa</strong> — Panel Administrator</span>
                <span>YLIC YO-FEST 2026</span>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('adminSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        document.getElementById('openSidebarBtn')?.addEventListener('click', () => {
            sidebar.classList.add('show');
            backdrop.classList.add('show');
        });
        const closeMenu = () => {
            sidebar.classList.remove('show');
            backdrop.classList.remove('show');
        };
        document.getElementById('closeSidebarBtn')?.addEventListener('click', closeMenu);
        backdrop?.addEventListener('click', closeMenu);
    </script>
</body>
</html>