<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('messages.student_dash_title') . ' — JagoBahasa')</title>

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'><rect width='64' height='64' rx='16' fill='%23047857'/><text x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' fill='white' font-family='sans-serif' font-weight='800' font-size='28'>JB</text></svg>">
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
                    <small class="text-muted" style="font-size: 0.72rem;">{{ __('messages.student_portal_label') }}</small>
                </div>
            </a>
            <!-- Tombol Tutup di Mobile -->
            <button class="btn btn-sm btn-light d-lg-none" id="closeSidebarBtn" aria-label="{{ __('messages.btn_close') }}">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Tombol Aksi Utama Murid: Gabung Kode Kelas -->
        <div class="px-3 pt-3 pb-2">
            <button type="button" class="btn btn-brand w-100 fw-semibold py-2 rounded-3 d-flex align-items-center justify-content-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#joinCourseModal">
                <i class="bi bi-plus-circle-fill"></i>
                <span>{{ __('messages.student_btn_join_code') }}</span>
            </button>
        </div>

        <!-- Menu Navigasi Utama Murid -->
        <div class="sidebar-menu flex-grow-1 px-3 py-2 overflow-y-auto">
            <small class="text-uppercase text-muted fw-bold px-2 d-block mb-2" style="font-size: 0.7rem; letter-spacing: 0.05em;">
                {{ __('messages.student_menu_heading') }}
            </small>
            <ul class="nav flex-column gap-1">
                <li class="nav-item">
                    <a href="{{ url('/student/dashboard') }}" class="student-nav-link {{ request()->is('student/dashboard*') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>{{ __('messages.nav_dashboard') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('student.courses.index') }}" class="student-nav-link {{ request()->is('student/courses*') ? 'active' : '' }}">
                        <i class="bi bi-journal-bookmark-fill"></i>
                        <span>{{ __('messages.student_my_courses') }}</span>
                        @if(($sidebarCoursesCount ?? 0) > 0)
                            <span class="badge bg-brand-soft text-brand ms-auto rounded-pill">{{ $sidebarCoursesCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('student.assignments.index') }}" class="student-nav-link {{ request()->is('student/assignments*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard2-check-fill"></i>
                        <span>{{ __('messages.student_tasks_quizzes') }}</span>
                        @if(($sidebarPendingTasksCount ?? 0) > 0)
                            <span class="badge bg-warning text-dark ms-auto rounded-pill">{{ $sidebarPendingTasksCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('student.live-classes') }}" class="student-nav-link {{ request()->is('student/live-classes*') ? 'active' : '' }}">
                        <i class="bi bi-camera-video-fill"></i>
                        <span>{{ __('messages.nav_live_classes') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('student.calendar') }}" class="student-nav-link {{ request()->is('student/calendar*') ? 'active' : '' }}">
                        <i class="bi bi-calendar3"></i>
                        <span>{{ __('messages.student_schedule_calendar') }}</span>
                    </a>
                </li>
            </ul>

            <small class="text-uppercase text-muted fw-bold px-2 d-block mt-4 mb-2" style="font-size: 0.7rem; letter-spacing: 0.05em;">
                {{ __('messages.student_achievement_heading') }}
            </small>
            <ul class="nav flex-column gap-1">
                <li class="nav-item">
                    <a href="{{ route('student.grades') }}" class="student-nav-link {{ request()->is('student/grades*') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-line-fill"></i>
                        <span>{{ __('messages.student_grades_progress') }}</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Info Ringkas Akun di Bawah Sidebar -->
        <div class="p-3 border-top bg-light">
            <div class="d-flex align-items-center gap-2">
                <div class="avatar-circle bg-brand text-white fw-bold flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name ?? 'M', 0, 1)) }}
                </div>
                <div class="overflow-hidden flex-grow-1">
                    <div class="fw-bold small text-truncate">{{ Auth::user()->name ?? 'Murid JagoBahasa' }}</div>
                    <div class="text-muted text-truncate" style="font-size: 0.75rem;">
                        <span class="badge bg-brand-soft text-brand border border-success border-opacity-25">{{ __('messages.student_role_badge') }}</span>
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
        <header class="student-topbar sticky-top bg-white border-bottom px-2 px-sm-3 px-lg-4 py-2">
            <div class="d-flex align-items-center justify-content-between gap-1 gap-sm-2" style="min-height: 48px;">
                <!-- Kiri: Tombol Hamburger Mobile + Breadcrumb / Judul Halaman -->
                <div class="d-flex align-items-center gap-2 min-w-0 flex-grow-1 overflow-hidden">
                    <button class="btn btn-light btn-sm border d-lg-none flex-shrink-0 px-2 py-1" id="openSidebarBtn" aria-label="Buka Menu">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <div class="min-w-0 flex-grow-1">
                        <h6 class="fw-bold mb-0 font-display text-truncate" style="font-size: clamp(0.92rem, 3.8vw, 1.05rem);">
                            @yield('page_title', __('messages.student_dash_title'))
                        </h6>
                        <small class="text-muted d-none d-sm-block text-truncate">@yield('page_subtitle', __('messages.student_dash_sub'))</small>
                    </div>
                </div>

                <!-- Kanan: Switcher Bahasa, Notifikasi & Profil -->
                <div class="d-flex align-items-center gap-1 gap-sm-2 flex-shrink-0">
                    <!-- Switcher Bahasa -->
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm border dropdown-toggle fw-semibold rounded-pill px-2 px-sm-3" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.78rem;">
                            <i class="bi bi-globe2 d-none d-sm-inline me-1"></i>{{ strtoupper(app()->getLocale()) }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2">
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

                    <!-- Notifikasi Tugas / Kelas -->
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm border rounded-3 position-relative px-2" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('messages.notifications_title') }}">
                            <i class="bi bi-bell"></i>
                            @if(($studentNotifications ?? collect())->isNotEmpty())
                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 p-2 mt-2" style="width: min(300px, calc(100vw - 24px));">
                            <li class="px-2 py-1 d-flex justify-content-between align-items-center border-bottom mb-1">
                                <span class="fw-bold small">{{ __('messages.notifications_title') }}</span>
                                @if(($studentNotifications ?? collect())->isNotEmpty())
                                    <span class="badge bg-brand-soft text-brand rounded-pill">{{ $studentNotifications->count() }}</span>
                                @endif
                            </li>

                            @forelse($studentNotifications ?? [] as $notif)
                                <li>
                                    <a class="dropdown-item rounded-2 py-2 small text-wrap d-flex align-items-start gap-2" href="{{ $notif['url'] }}">
                                        <i class="bi {{ $notif['icon'] }} {{ $notif['color'] }} mt-1"></i>
                                        <div>
                                            <div class="fw-semibold text-dark lh-sm mb-1">{{ $notif['title'] }}</div>
                                            <span class="text-muted d-block" style="font-size: 0.74rem;">{{ $notif['subtitle'] }}</span>
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li class="px-2 py-4 text-center text-muted small">
                                    <i class="bi bi-bell-slash d-block fs-5 mb-1"></i>
                                    {{ __('messages.notifications_empty') }}
                                </li>
                            @endforelse
                        </ul>
                    </div>

                    <!-- Dropdown Profil Murid -->
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle bg-light border rounded-pill p-1 pe-2 pe-md-3" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="avatar-circle bg-brand text-white fw-bold me-1 me-md-2" style="width: 26px; height: 26px; font-size: 0.78rem;">
                                {{ strtoupper(substr(Auth::user()->name ?? 'M', 0, 1)) }}
                            </div>
                            <span class="d-none d-md-inline text-dark fw-semibold small">
                                {{ Auth::user()->name ?? __('messages.student_role_badge') }}
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2">
                            <li><a class="dropdown-item" href="{{ route('student.profile.edit') }}"><i class="bi bi-person me-2"></i>{{ __('messages.student_my_profile') }}</a></li>
                            <li><a class="dropdown-item" href="{{ url('/') }}"><i class="bi bi-house me-2"></i>{{ __('messages.student_front_page') }}</a></li>
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
                </div>
            </div>
        </header>

        <!-- KONTEN DINAMIS MURID -->
        <main class="flex-grow-1 p-3 p-lg-4">
            <div class="container-fluid px-0" style="max-width: 1280px;">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('messages.btn_close') }}"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('messages.btn_close') }}"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>{{ $errors->first() }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('messages.btn_close') }}"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <!-- FOOTER RINGKAS PORTAL MURID -->
        <footer class="bg-white border-top py-3 px-3 px-lg-4 small text-muted">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <span>&copy; {{ date('Y') }} <strong>JagoBahasa</strong> — {{ __('messages.student_footer_label') }}</span>
                <a href="#" class="text-muted text-decoration-none"><i class="bi bi-question-circle me-1"></i>{{ __('messages.help_center') }}</a>
            </div>
        </footer>
    </div>

    <!-- MODAL GABUNG KODE KELAS -->
    <div class="modal fade" id="joinCourseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold">{{ __('messages.modal_join_class_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.btn_close') }}"></button>
                </div>
                <form action="{{ route('student.courses.join') }}" method="POST">
                    @csrf
                    <div class="modal-body pt-0">
                        <p class="text-muted small">{{ __('messages.modal_join_class_desc') }}</p>
                        <input type="text" name="class_code" class="form-control form-control-lg text-uppercase fw-bold text-center tracking-widest" placeholder="{{ __('messages.modal_join_class_placeholder') }}" required>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">{{ __('messages.btn_cancel') }}</button>
                        <button type="submit" class="btn btn-brand rounded-3 px-4">{{ __('messages.student_btn_join_submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('studentSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        const openBtn = document.getElementById('openSidebarBtn');
        const closeBtn = document.getElementById('closeSidebarBtn');

        const toggleSidebar = (show) => {
            if (show) {
                sidebar?.classList.add('show');
                backdrop?.classList.add('show');
                document.body.style.overflow = 'hidden';
            } else {
                sidebar?.classList.remove('show');
                backdrop?.classList.remove('show');
                document.body.style.overflow = '';
            }
        };

        openBtn?.addEventListener('click', () => toggleSidebar(true));
        closeBtn?.addEventListener('click', () => toggleSidebar(false));
        backdrop?.addEventListener('click', () => toggleSidebar(false));
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') toggleSidebar(false);
        });
    </script>
    @stack('scripts')
</body>
</html>