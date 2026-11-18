<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Murid — JagoBahasa')</title>

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Vite Custom CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="student-shell">

    <!-- SIDEBAR MURID (Desktop Fixed + Mobile Offcanvas) -->
    <aside class="student-sidebar d-flex flex-column" id="studentSidebar">
        <!-- Header Brand Sidebar -->
        <div class="sidebar-header d-flex align-items-center justify-content-between px-4 py-3 border-bottom">
            <a href="{{ url('/') }}" class="d-flex align-items-center gap-2 text-decoration-none text-brand">
                <span class="brand-icon d-inline-flex align-items-center justify-content-center rounded-3 text-white">
                    <i class="bi bi-translate"></i>
                </span>
                <div>
                    <span class="font-display fw-extrabold fs-5 d-block lh-1">JagoBahasa</span>
                    <small class="text-muted" style="font-size: 0.72rem;">Portal Murid</small>
                </div>
            </a>
            <!-- Tombol Tutup di Mobile -->
            <button class="btn btn-sm btn-light d-lg-none" id="closeSidebarBtn" aria-label="Tutup Menu">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Tombol Aksi Utama Murid: Gabung Kode Kelas -->
        <div class="px-3 pt-3 pb-2">
            <button type="button" class="btn btn-brand w-100 fw-semibold py-2 rounded-3 d-flex align-items-center justify-content-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#joinCourseModal">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Gabung Kode Kelas</span>
            </button>
        </div>

        <!-- Menu Navigasi Utama Murid -->
        <div class="sidebar-menu flex-grow-1 px-3 py-2 overflow-y-auto">
            <small class="text-uppercase text-muted fw-bold px-2 d-block mb-2" style="font-size: 0.7rem; letter-spacing: 0.05em;">
                Menu Belajar
            </small>
            <ul class="nav flex-column gap-1">
                <li class="nav-item">
                    <a href="{{ url('/student/dashboard') }}" class="student-nav-link {{ request()->is('student/dashboard*') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/student/courses') }}" class="student-nav-link {{ request()->is('student/courses*') ? 'active' : '' }}">
                        <i class="bi bi-journal-bookmark-fill"></i>
                        <span>Kursus Saya</span>
                        <span class="badge bg-brand-soft text-brand ms-auto rounded-pill">3</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/student/assignments') }}" class="student-nav-link {{ request()->is('student/assignments*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard2-check-fill"></i>
                        <span>Tugas &amp; Kuis</span>
                        <span class="badge bg-warning text-dark ms-auto rounded-pill">2</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/student/live-classes') }}" class="student-nav-link {{ request()->is('student/live-classes*') ? 'active' : '' }}">
                        <i class="bi bi-camera-video-fill"></i>
                        <span>Kelas Online</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/student/calendar') }}" class="student-nav-link {{ request()->is('student/calendar*') ? 'active' : '' }}">
                        <i class="bi bi-calendar3"></i>
                        <span>Kalender Jadwal</span>
                    </a>
                </li>
            </ul>

            <small class="text-uppercase text-muted fw-bold px-2 d-block mt-4 mb-2" style="font-size: 0.7rem; letter-spacing: 0.05em;">
                Pencapaian
            </small>
            <ul class="nav flex-column gap-1">
                <li class="nav-item">
                    <a href="{{ url('/student/grades') }}" class="student-nav-link {{ request()->is('student/grades*') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-line-fill"></i>
                        <span>Nilai &amp; Progres</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Info Ringkas Akun di Bawah Sidebar -->
        <div class="p-3 border-top bg-light">
            <div class="d-flex align-items-center gap-2">
                <div class="avatar-circle bg-brand text-white fw-bold flex-shrink-0">
                    {{ substr(Auth::user()->name ?? 'M', 0, 1) }}
                </div>
                <div class="overflow-hidden flex-grow-1">
                    <div class="fw-bold small text-truncate">{{ Auth::user()->name ?? 'Murid JagoBahasa' }}</div>
                    <div class="text-muted text-truncate" style="font-size: 0.75rem;">
                        <span class="badge bg-brand-soft text-brand border border-success border-opacity-25">Murid</span>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- BACKDROP OVERLAY MOBILE -->
    <div class="sidebar-backdrop d-lg-none" id="sidebarBackdrop"></div>

    <!-- AREA KONTEN KANAN (Topbar + Main Content + Footer) -->
    <div class="student-main-wrapper d-flex flex-column min-vh-100">

        <!-- TOPBAR MURID -->
        <header class="student-topbar sticky-top bg-white border-bottom px-3 px-lg-4 py-2">
            <div class="d-flex align-items-center justify-content-between" style="min-height: 52px;">
                <!-- Kiri: Tombol Hamburger Mobile + Breadcrumb / Judul Halaman -->
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-light border d-lg-none" id="openSidebarBtn" aria-label="Buka Menu">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <div>
                        <h6 class="fw-bold mb-0 font-display">@yield('page_title', 'Dashboard Belajar')</h6>
                        <small class="text-muted d-none d-sm-inline">@yield('page_subtitle', 'Pantau progres kursus dan tugas bahasa Anda')</small>
                    </div>
                </div>

                <!-- Kanan: Switcher Bahasa, Notifikasi, Bantuan & Profil -->
                <div class="d-flex align-items-center gap-2">
                    <!-- Pengganti Bahasa -->
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm border dropdown-toggle fw-medium rounded-3 px-2 px-sm-3" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-globe2 me-1"></i>ID
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                            <li><a class="dropdown-item active" href="#">Bahasa Indonesia (ID)</a></li>
                            <li><a class="dropdown-item" href="#">English (EN)</a></li>
                        </ul>
                    </div>

                    <!-- Notifikasi Tugas / Kelas -->
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm border rounded-3 position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi">
                            <i class="bi bi-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 p-2" style="width: 280px;">
                            <li class="px-2 py-1 fw-bold small border-bottom mb-1">Pemberitahuan Terbaru</li>
                            <li>
                                <a class="dropdown-item rounded-2 py-2 small text-wrap" href="#">
                                    <div class="fw-semibold text-brand">Tugas Baru: Speaking Practice</div>
                                    <span class="text-muted" style="font-size: 0.75rem;">Tenggat besok pukul 23:59 WIB</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item rounded-2 py-2 small text-wrap" href="#">
                                    <div class="fw-semibold">Kelas Live dimulai 30 menit lagi</div>
                                    <span class="text-muted" style="font-size: 0.75rem;">Academic English — Ruang A</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Dropdown Profil Murid -->
                    <div class="dropdown ms-1">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle bg-light border rounded-pill py-1 px-2 pe-3" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="avatar-circle bg-brand text-white fw-bold me-2" style="width: 28px; height: 28px; font-size: 0.8rem;">
                                {{ substr(Auth::user()->name ?? 'M', 0, 1) }}
                            </div>
                            <span class="d-none d-md-inline text-dark fw-semibold small">
                                {{ Auth::user()->name ?? 'Murid' }}
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profil Saya</a></li>
                            <li><a class="dropdown-item" href="{{ url('/') }}"><i class="bi bi-house me-2"></i>Halaman Depan</a></li>
                            <li><hr class="dropdown-divider"></li>
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

        <!-- KONTEN DINAMIS MURID -->
        <main class="flex-grow-1 p-3 p-lg-4">
            <div class="container-fluid px-0" style="max-width: 1280px;">
                @yield('content')
            </div>
        </main>

        <!-- FOOTER RINGKAS PORTAL MURID -->
        <footer class="bg-white border-top py-3 px-4 small text-muted">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <span>&copy; {{ date('Y') }} <strong>JagoBahasa</strong> — Portal Pembelajaran Murid</span>
                <a href="#" class="text-muted text-decoration-none"><i class="bi bi-question-circle me-1"></i>Pusat Bantuan</a>
            </div>
        </footer>
    </div>

    <!-- MODAL GABUNG KODE KELAS -->
    <div class="modal fade" id="joinCourseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold">Gabung Kelas dengan Kode</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form action="{{ route('student.courses.join') }}" method="POST">
                    @csrf
                    <div class="modal-body pt-0">
                        <p class="text-muted small">Minta kode kelas (6–8 karakter) dari pengajar Anda, lalu masukkan di bawah ini.</p>
                        <input type="text" name="class_code" class="form-control form-control-lg text-uppercase fw-bold text-center tracking-widest" placeholder="Contoh: JGB-EN01" required>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-brand rounded-3 px-4">Gabung Kelas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Sidebar Mobile
        const sidebar = document.getElementById('studentSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        const openBtn = document.getElementById('openSidebarBtn');
        const closeBtn = document.getElementById('closeSidebarBtn');

        const toggleSidebar = (show) => {
            if (show) {
                sidebar.classList.add('show');
                backdrop.classList.add('show');
            } else {
                sidebar.classList.remove('show');
                backdrop.classList.remove('show');
            }
        };

        openBtn?.addEventListener('click', () => toggleSidebar(true));
        closeBtn?.addEventListener('click', () => toggleSidebar(false));
        backdrop?.addEventListener('click', () => toggleSidebar(false));
    </script>
    @stack('scripts')
</body>
</html>