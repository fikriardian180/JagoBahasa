<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Pengajar — JagoBahasa')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'><rect width='64' height='64' rx='16' fill='%23047857'/><text x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' fill='white' font-family='sans-serif' font-weight='800' font-size='28'>JB</text></svg>">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="student-shell">

    <!-- SIDEBAR PENGAJAR -->
    <aside class="student-sidebar d-flex flex-column" id="teacherSidebar">
        <div class="sidebar-header d-flex align-items-center justify-content-between px-4 py-3 border-bottom">
            <a href="{{ url('/') }}" class="d-flex align-items-center gap-2 text-decoration-none text-brand">
                <span class="brand-icon d-inline-flex align-items-center justify-content-center rounded-3 text-white">
                    <i class="bi bi-translate"></i>
                </span>
                <div>
                    <span class="font-display fw-extrabold fs-5 d-block lh-1">JagoBahasa</span>
                    <small class="text-muted" style="font-size: 0.72rem;">Portal Pengajar</small>
                </div>
            </a>
            <button class="btn btn-sm btn-light d-lg-none" id="closeSidebarBtn" aria-label="Tutup Menu">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Tombol Aksi Utama Pengajar: Buat Kelas Baru -->
        <div class="px-3 pt-3 pb-2">
            <a href="{{ route('teacher.courses.create') }}" class="btn btn-brand w-100 fw-semibold py-2 rounded-3 d-flex align-items-center justify-content-center gap-2 shadow-sm">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Buat Kelas Baru</span>
            </a>
        </div>

        <!-- Menu Navigasi Pengajar -->
        <div class="sidebar-menu flex-grow-1 px-3 py-2 overflow-y-auto">
            <small class="text-uppercase text-muted fw-bold px-2 d-block mb-2" style="font-size: 0.7rem; letter-spacing: 0.05em;">
                Manajemen Mengajar
            </small>
            <ul class="nav flex-column gap-1">
                <li class="nav-item">
                    <a href="{{ route('teacher.dashboard') }}" class="student-nav-link {{ request()->is('teacher/dashboard*') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('teacher.courses.index') }}" class="student-nav-link {{ request()->is('teacher/courses*') ? 'active' : '' }}">
                        <i class="bi bi-easel2-fill"></i>
                        <span>Kelas Diampu</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('teacher.assignments.index') }}" class="student-nav-link {{ request()->is('teacher/assignments*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard2-data-fill"></i>
                        <span>Tugas &amp; Penilaian</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('teacher.live-classes.index') }}" class="student-nav-link {{ request()->is('teacher/live-classes*') ? 'active' : '' }}">
                        <i class="bi bi-camera-video-fill"></i>
                        <span>Jadwal Kelas Online</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Info Akun Pengajar -->
        <div class="p-3 border-top bg-light">
            <div class="d-flex align-items-center gap-2">
                <div class="avatar-circle bg-brand text-white fw-bold flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name ?? 'G', 0, 1)) }}
                </div>
                <div class="overflow-hidden flex-grow-1">
                    <div class="fw-bold small text-truncate">{{ Auth::user()->name ?? 'Pengajar' }}</div>
                    <span class="badge bg-warning bg-opacity-25 text-dark border border-warning border-opacity-50" style="font-size: 0.7rem;">
                        Pengajar / Instruktur
                    </span>
                </div>
            </div>
        </div>
    </aside>

    <div class="sidebar-backdrop d-lg-none" id="sidebarBackdrop"></div>

    <!-- AREA KONTEN KANAN -->
    <div class="student-main-wrapper d-flex flex-column min-vh-100">
        <header class="student-topbar sticky-top bg-white border-bottom px-3 px-lg-4 py-2">
            <div class="d-flex align-items-center justify-content-between gap-2" style="min-height: 52px;">
                <div class="d-flex align-items-center gap-2 gap-sm-3 min-w-0 flex-grow-1">
                    <button class="btn btn-light border d-lg-none flex-shrink-0" id="openSidebarBtn" aria-label="Buka Menu">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <div class="min-w-0">
                        <h6 class="fw-bold mb-0 font-display text-truncate">@yield('page_title', 'Dashboard Pengajar')</h6>
                        <small class="text-muted d-none d-sm-block text-truncate">@yield('page_subtitle', 'Kelola kurikulum, materi, dan evaluasi peserta didik')</small>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle bg-light border rounded-pill py-1 px-2 pe-2 pe-md-3" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="avatar-circle bg-brand text-white fw-bold me-1 me-md-2" style="width: 28px; height: 28px; font-size: 0.8rem;">
                                {{ strtoupper(substr(Auth::user()->name ?? 'G', 0, 1)) }}
                            </div>
                            <span class="d-none d-md-inline text-dark fw-semibold small">{{ Auth::user()->name ?? 'Pengajar' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2">
                            <li><a class="dropdown-item" href="{{ route('teacher.profile.edit') }}"><i class="bi bi-person me-2"></i>Profil Saya</a></li>
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
            </div>
        </header>

        <main class="flex-grow-1 p-3 p-lg-4">
            <div class="container-fluid px-0" style="max-width: 1280px;">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>{{ $errors->first() }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <footer class="bg-white border-top py-3 px-3 px-lg-4 small text-muted">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <span>&copy; {{ date('Y') }} <strong>JagoBahasa</strong> — Portal Pengajar</span>
                <span>YLIC YO-FEST 2026</span>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('teacherSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');

        const openMenu = () => {
            sidebar?.classList.add('show');
            backdrop?.classList.add('show');
            document.body.style.overflow = 'hidden';
        };

        const closeMenu = () => {
            sidebar?.classList.remove('show');
            backdrop?.classList.remove('show');
            document.body.style.overflow = '';
        };

        document.getElementById('openSidebarBtn')?.addEventListener('click', openMenu);
        document.getElementById('closeSidebarBtn')?.addEventListener('click', closeMenu);
        backdrop?.addEventListener('click', closeMenu);
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeMenu();
        });
    </script>
    @stack('scripts')
</body>
</html>