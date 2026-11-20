<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('messages.site_title'))</title>

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'><rect width='64' height='64' rx='16' fill='%23047857'/><text x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' fill='white' font-family='sans-serif' font-weight='800' font-size='28'>JB</text></svg>">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- Vite Custom CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- HEADER & NAVBAR -->
    <header class="sticky-top">
        <nav class="navbar navbar-expand-lg navbar-jb py-2 py-lg-3" id="mainNav">
            <div class="container-fluid px-3 px-sm-4 px-lg-5">
                <!-- Brand / Logo -->
                <a class="navbar-brand fw-bold d-flex align-items-center gap-2 text-brand" href="{{ url('/') }}">
                    <span class="brand-icon d-inline-flex align-items-center justify-content-center rounded-3 text-white">
                        <i class="bi bi-translate"></i>
                    </span>
                    <span class="font-display fw-extrabold fs-4">Jago Bahasa</span>
                </a>

                <!-- Tombol Toggle Mobile -->
                <button class="navbar-toggler border-0 shadow-none p-1" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="bi bi-list fs-1 text-dark"></i>
                </button>

                <!-- Menu Navigasi -->
                <div class="collapse navbar-collapse" id="mainNavbar">
                    @auth
                        <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-1 gap-lg-2">
                            @if (Auth::user()->role === 'admin')
                                <li class="nav-item"><a class="nav-link active fw-medium" href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
                                <li class="nav-item"><a class="nav-link fw-medium" href="{{ route('admin.users') }}">Data Murid &amp; Guru</a></li>
                                <li class="nav-item"><a class="nav-link fw-medium" href="{{ route('admin.grades') }}">Rekap Nilai</a></li>
                            @elseif (Auth::user()->role === 'teacher')
                                <li class="nav-item"><a class="nav-link active fw-medium" href="{{ route('teacher.dashboard') }}">Dashboard Guru</a></li>
                                <li class="nav-item"><a class="nav-link fw-medium" href="{{ route('teacher.courses.index') }}">Kelas Diampu</a></li>
                                <li class="nav-item"><a class="nav-link fw-medium" href="{{ route('teacher.assignments.index') }}">Tugas &amp; Penilaian</a></li>
                                <li class="nav-item"><a class="nav-link fw-medium" href="{{ route('teacher.live-classes.index') }}">Jadwal Live</a></li>
                            @else
                                <li class="nav-item"><a class="nav-link active fw-medium" href="{{ route('student.dashboard') }}">{{ __('messages.nav_dashboard') }}</a></li>
                                <li class="nav-item"><a class="nav-link fw-medium" href="{{ route('student.courses.index') }}">{{ __('messages.nav_courses') }}</a></li>
                                <li class="nav-item"><a class="nav-link fw-medium" href="{{ route('student.assignments.index') }}">{{ __('messages.nav_assignments') }}</a></li>
                                <li class="nav-item"><a class="nav-link fw-medium" href="{{ route('student.live-classes') }}">{{ __('messages.nav_live_classes') }}</a></li>
                                <li class="nav-item"><a class="nav-link fw-medium" href="{{ route('student.calendar') }}">{{ __('messages.nav_calendar') }}</a></li>
                            @endif
                        </ul>
                    @else
                        <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-1 gap-lg-2">
                            <li class="nav-item"><a class="nav-link fw-medium" href="#beranda">{{ __('messages.nav_home') }}</a></li>
                            <li class="nav-item"><a class="nav-link fw-medium" href="#metode">{{ __('messages.nav_method') }}</a></li>
                            <li class="nav-item"><a class="nav-link fw-medium" href="#katalog">{{ __('messages.nav_programs') }}</a></li>
                            <li class="nav-item"><a class="nav-link fw-medium" href="#panduan">{{ __('messages.nav_join_guide') }}</a></li>
                        </ul>
                    @endauth

                    <!-- Aksi Kanan -->
                    <div class="d-flex align-items-center gap-2 flex-wrap pt-2 pt-lg-0 mt-2 mt-lg-0 border-top border-lg-0 ms-lg-auto">
                        @auth
                            @if (Auth::user()->role === 'student')
                                <button type="button" class="btn btn-outline-brand btn-sm fw-semibold px-3 rounded-3" data-bs-toggle="modal" data-bs-target="#joinCourseModal">
                                    <i class="bi bi-plus-lg me-1"></i>{{ __('messages.btn_join_code') }}
                                </button>
                            @endif
                        @endauth

                        <!-- Menu Pengganti Bahasa (Hanya untuk Tamu & Murid) -->
                        @if (!auth()->check() || Auth::user()->role === 'student')
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm border dropdown-toggle fw-medium rounded-3 px-3" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-globe2 me-1"></i>{{ strtoupper(app()->getLocale()) }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-lg-end shadow-sm border-0 rounded-3">
                                    <li>
                                        <a class="dropdown-item {{ app()->getLocale() === 'id' ? 'active' : '' }}" href="{{ route('lang.switch', 'id') }}">
                                            Bahasa Indonesia (ID)
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}" href="{{ route('lang.switch', 'en') }}">
                                            English (EN)
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @endif

                        <!-- Menu Bantuan -->
                        <a href="#panduan" class="btn btn-light btn-sm border rounded-3" title="{{ __('messages.help_title') }}">
                            <i class="bi bi-question-circle"></i>
                        </a>

                        @guest
                            <a href="{{ route('login') }}" class="btn btn-outline-brand btn-sm fw-semibold px-3 rounded-3">
                                <i class="bi bi-box-arrow-in-right me-1"></i>{{ __('messages.btn_login') }}
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-brand btn-sm fw-semibold px-3 rounded-3">
                                {{ __('messages.btn_register') }}
                            </a>
                        @endguest

                        @auth
                            @php
                                $dashboardUrl = match(Auth::user()->role) {
                                    'admin' => route('admin.dashboard'),
                                    'teacher' => route('teacher.dashboard'),
                                    default => route('student.dashboard'),
                                };
                            @endphp

                            <div class="dropdown ms-auto ms-lg-0">
                                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle ps-1 ps-lg-2" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="avatar-circle bg-brand text-white fw-bold me-2">
                                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <span class="d-inline d-lg-none d-xl-inline text-dark fw-medium small text-truncate" style="max-width: 140px;">
                                        {{ Auth::user()->name ?? __('messages.profile_fallback') }}
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                                    <li>
                                        <a class="dropdown-item" href="{{ $dashboardUrl }}">
                                            <i class="bi bi-speedometer2 me-2"></i>{{ __('messages.btn_my_dashboard') }}
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-box-arrow-right me-2"></i>{{ __('messages.btn_logout') }}
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- KONTEN HALAMAN -->
    @hasSection('fullwidth_content')
        <main class="flex-grow-1">
            @yield('fullwidth_content')
        </main>
    @else
        <main class="container py-4 py-lg-5 flex-grow-1">
            @yield('content')
        </main>
    @endif

    <!-- FOOTER -->
    <footer class="bg-white border-top py-4 py-lg-5 mt-auto">
        <div class="container">
            <div class="row gy-4 align-items-start">
                <div class="col-lg-5">
                    <a class="d-inline-flex align-items-center gap-2 text-brand text-decoration-none fw-bold fs-4 font-display" href="{{ url('/') }}">
                        <span class="brand-icon d-inline-flex align-items-center justify-content-center rounded-3 text-white">
                            <i class="bi bi-translate"></i>
                        </span>
                        <span>JagoBahasa</span>
                    </a>
                    <p class="text-muted small mt-3 mb-0" style="max-width: 380px; line-height: 1.7;">
                        {{ __('messages.footer_desc') }}
                    </p>
                </div>
                <div class="col-6 col-lg-3">
                    <h6 class="fw-bold font-display mb-3">{{ __('messages.footer_nav_heading') }}</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                        <li><a href="#beranda" class="text-muted text-decoration-none">{{ __('messages.nav_home') }}</a></li>
                        <li><a href="#metode" class="text-muted text-decoration-none">{{ __('messages.nav_method') }}</a></li>
                        <li><a href="#katalog" class="text-muted text-decoration-none">{{ __('messages.nav_programs') }}</a></li>
                        <li><a href="#panduan" class="text-muted text-decoration-none">{{ __('messages.nav_join_guide') }}</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-4">
                    <h6 class="fw-bold font-display mb-3">{{ __('messages.footer_quick_heading') }}</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                        <li><a href="{{ route('login') }}" class="text-muted text-decoration-none">{{ __('messages.footer_login_link') }}</a></li>
                        <li><a href="#panduan" class="text-muted text-decoration-none">{{ __('messages.footer_join_link') }}</a></li>
                        <li><a href="#katalog" class="text-muted text-decoration-none">{{ __('messages.footer_syllabus_link') }}</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 opacity-25">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center text-center text-sm-start gap-2 small text-muted">
                <span>&copy; {{ date('Y') }} JagoBahasa LMS. {{ __('messages.footer_copyright') }}</span>
                <span>{{ __('messages.footer_version') }}</span>
            </div>
        </div>
    </footer>

    <!-- MODAL GABUNG KURSUS -->
    @auth
        @if (Auth::user()->role === 'student')
            <div class="modal fade" id="joinCourseModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form action="{{ route('student.courses.join') }}" method="POST" class="modal-content border-0 shadow rounded-4">
                        @csrf
                        <div class="modal-header border-bottom-0">
                            <h5 class="modal-title fw-bold">{{ __('messages.modal_join_title') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.btn_close') }}"></button>
                        </div>
                        <div class="modal-body pt-0">
                            <p class="text-muted small">{{ __('messages.modal_join_desc') }}</p>
                            <input type="text" name="class_code" class="form-control form-control-lg text-uppercase font-monospace" placeholder="{{ __('messages.modal_join_placeholder') }}" required>
                        </div>
                        <div class="modal-footer border-top-0">
                            <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">{{ __('messages.btn_cancel') }}</button>
                            <button type="submit" class="btn btn-brand rounded-3 px-4 fw-semibold">{{ __('messages.btn_join') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endauth

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 750, easing: 'ease-out-quart', once: true });

        const nav = document.getElementById('mainNav');
        const navLinks = document.querySelectorAll('#mainNavbar .nav-link[href^="#"]');
        const sections = document.querySelectorAll('section[id]');

        const handleScroll = () => {
            if (window.scrollY > 30) nav.classList.add('scrolled');
            else nav.classList.remove('scrolled');

            let currentSectionId = '';
            const scrollPosition = window.scrollY + 140;

            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.offsetHeight;
                if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                    currentSectionId = section.getAttribute('id');
                }
            });

            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 20) {
                currentSectionId = 'panduan';
            }

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (currentSectionId && link.getAttribute('href') === `#${currentSectionId}`) {
                    link.classList.add('active');
                }
            });
        };

        window.addEventListener('scroll', handleScroll);
        window.addEventListener('load', handleScroll);

        navLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                const targetId = this.getAttribute('href');
                const targetEl = document.querySelector(targetId);
                if (targetEl) {
                    e.preventDefault();
                    const yOffset = -80;
                    const y = targetEl.getBoundingClientRect().top + window.scrollY + yOffset;
                    window.scrollTo({ top: y, behavior: 'smooth' });

                    const navbarCollapse = document.getElementById('mainNavbar');
                    if (navbarCollapse && navbarCollapse.classList.contains('show')) {
                        bootstrap.Collapse.getInstance(navbarCollapse)?.hide();
                    }
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>