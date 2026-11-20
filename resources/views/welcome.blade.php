@extends('layouts.app')

@section('title', __('messages.welcome_page_title'))

@section('fullwidth_content')
    @php
        $dashboardUrl = auth()->check()
            ? match(Auth::user()->role) {
                'admin' => route('admin.dashboard'),
                'teacher' => route('teacher.dashboard'),
                default => route('student.dashboard'),
            }
            : route('login');
    @endphp

    <!-- 1. HERO SECTION: INTERAKTIF & BERKARAKTER LMS -->
    <section class="hero-lms" id="beranda">
        <div class="container">
            <div class="row align-items-center gy-5">
                <!-- Kolom Kiri -->
                <div class="col-lg-6" data-aos="fade-up">
                    <div class="greeting-pill mb-3">
                        <i class="bi bi-chat-dots-fill"></i>
                        <span id="liveGreeting">{{ __('messages.hero_badge') }}</span>
                    </div>

                    <h1 class="display-5 fw-extrabold mb-3" style="line-height: 1.2;">
                        {{ __('messages.hero_title_prefix') }} <span class="dynamic-word" id="rotatingLang">{{ __('messages.lang_en_name') }}</span> {{ __('messages.hero_title_suffix') }}
                    </h1>

                    <p class="lead text-muted mb-4" style="font-size: 1.05rem; line-height: 1.75;">
                        {{ __('messages.hero_subtitle') }}
                    </p>

                    <div class="d-flex flex-wrap gap-3">
                        @auth
                            <a href="{{ $dashboardUrl }}" class="btn btn-brand btn-lg fw-semibold px-4 rounded-3">
                                {{ __('messages.hero_cta_dashboard') }} <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-brand btn-lg fw-semibold px-4 rounded-3">
                                {{ __('messages.hero_cta_start') }} <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                            <a href="#metode" class="btn btn-outline-brand btn-lg fw-semibold px-4 rounded-3">
                                {{ __('messages.hero_cta_explore') }}
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Kolom Kanan: Simulasi Pratinjau UI LMS -->
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="150">
                    <div class="lms-preview-card">
                        <div class="lms-window-bar">
                            <div class="window-dots">
                                <span style="background: #ef4444;"></span>
                                <span style="background: #f59e0b;"></span>
                                <span style="background: #10b981;"></span>
                            </div>
                            <small class="text-muted fw-medium">{{ __('messages.preview_header') }}</small>
                            <span class="badge bg-brand-soft text-brand">{{ __('messages.preview_status') }}</span>
                        </div>

                        <div class="p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="fw-bold mb-0">{{ __('messages.preview_courses_title') }}</h6>
                                    <small class="text-muted">{{ __('messages.preview_courses_sub') }}</small>
                                </div>
                                <span class="badge bg-brand rounded-pill px-3 py-2">{{ __('messages.preview_active_badge') }}</span>
                            </div>

                            <!-- Item Kelas Simulasi 1 -->
                            <div class="course-mini-item mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-brand-soft text-brand">EN-201</span>
                                        <span class="fw-bold small">Academic English &amp; Speaking</span>
                                    </div>
                                    <small class="fw-bold text-brand">80%</small>
                                </div>
                                <div class="progress" style="height: 7px;">
                                    <div class="progress-bar bg-brand" role="progressbar" style="width: 80%;"></div>
                                </div>
                            </div>

                            <!-- Item Kelas Simulasi 2 -->
                            <div class="course-mini-item mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-warning bg-opacity-25 text-dark">AR-102</span>
                                        <span class="fw-bold small">Bahasa Arab: Maharah Kalam</span>
                                    </div>
                                    <small class="fw-bold text-brand">65%</small>
                                </div>
                                <div class="progress" style="height: 7px;">
                                    <div class="progress-bar bg-brand" role="progressbar" style="width: 65%;"></div>
                                </div>
                            </div>

                            <!-- Jadwal Kelas Berikutnya -->
                            <div class="p-3 rounded-3 bg-light border d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-brand text-white rounded-3 p-2 px-3 text-center">
                                        <i class="bi bi-camera-video-fill fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold small">{{ __('messages.preview_live_title') }}</div>
                                        <small class="text-muted">{{ __('messages.preview_live_sub') }}</small>
                                    </div>
                                </div>
                                <span class="badge bg-white text-dark border">{{ __('messages.preview_ready') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. METRIK PEMBELAJARAN -->
    <section class="py-5" id="metrics">
        <div class="container">
            <div class="row g-4">
                <div class="col-6 col-lg-3" data-aos="fade-up">
                    <div class="metric-box">
                        <h3 class="fw-extrabold text-brand mb-1"><span class="counter" data-target="2">0</span> {{ __('messages.metric_lang_unit') }}</h3>
                        <small class="text-muted">{{ __('messages.metric_lang_desc') }}</small>
                    </div>
                </div>
                <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="80">
                    <div class="metric-box">
                        <h3 class="fw-extrabold text-brand mb-1"><span class="counter" data-target="50">0</span>{{ __('messages.metric_mod_unit') }}</h3>
                        <small class="text-muted">{{ __('messages.metric_mod_desc') }}</small>
                    </div>
                </div>
                <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="160">
                    <div class="metric-box">
                        <h3 class="fw-extrabold text-brand mb-1"><span class="counter" data-target="150">0</span>{{ __('messages.metric_quiz_unit') }}</h3>
                        <small class="text-muted">{{ __('messages.metric_quiz_desc') }}</small>
                    </div>
                </div>
                <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="240">
                    <div class="metric-box">
                        <h3 class="fw-extrabold text-brand mb-1"><span class="counter" data-target="24">0</span>{{ __('messages.metric_access_unit') }}</h3>
                        <small class="text-muted">{{ __('messages.metric_access_desc') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. METODE BELAJAR INTERAKTIF -->
    <section class="py-5 bg-white border-top border-bottom" id="metode">
        <div class="container py-lg-3">
            <div class="row align-items-center gy-4">
                <div class="col-lg-5" data-aos="fade-right">
                    <span class="badge bg-brand-soft text-brand fw-bold px-3 py-2 mb-2">{{ __('messages.method_badge') }}</span>
                    <h2 class="fw-extrabold mb-3">{{ __('messages.method_title') }}</h2>
                    <p class="text-muted mb-4">{{ __('messages.method_subtitle') }}</p>

                    <div class="nav flex-column nav-pills method-nav gap-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <button class="nav-link active" id="tab-materi" data-bs-toggle="pill" data-bs-target="#content-materi" type="button" role="tab">
                            <div class="fw-bold"><i class="bi bi-journal-richtext me-2"></i>{{ __('messages.tab1_title') }}</div>
                            <small class="text-muted d-block mt-1">{{ __('messages.tab1_sub') }}</small>
                        </button>

                        <button class="nav-link" id="tab-live" data-bs-toggle="pill" data-bs-target="#content-live" type="button" role="tab">
                            <div class="fw-bold"><i class="bi bi-camera-video me-2"></i>{{ __('messages.tab2_title') }}</div>
                            <small class="text-muted d-block mt-1">{{ __('messages.tab2_sub') }}</small>
                        </button>

                        <button class="nav-link" id="tab-tugas" data-bs-toggle="pill" data-bs-target="#content-tugas" type="button" role="tab">
                            <div class="fw-bold"><i class="bi bi-ui-checks me-2"></i>{{ __('messages.tab3_title') }}</div>
                            <small class="text-muted d-block mt-1">{{ __('messages.tab3_sub') }}</small>
                        </button>
                    </div>
                </div>

                <div class="col-lg-7" data-aos="fade-left" data-aos-delay="120">
                    <div class="tab-content p-4 p-md-5 rounded-4 border bg-light" id="v-pills-tabContent">
                        <!-- Tab 1 -->
                        <div class="tab-pane fade show active" id="content-materi" role="tabpanel">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="bg-brand text-white rounded-3 p-3"><i class="bi bi-journal-bookmark-fill fs-3"></i></div>
                                <div>
                                    <h4 class="fw-bold mb-0">{{ __('messages.tab1_panel_title') }}</h4>
                                    <small class="text-muted">{{ __('messages.tab1_panel_sub') }}</small>
                                </div>
                            </div>
                            <p class="text-muted">{{ __('messages.tab1_panel_desc') }}</p>
                            <ul class="list-unstyled d-flex flex-column gap-2 mb-0 small">
                                <li><i class="bi bi-check-circle-fill text-brand me-2"></i>{{ __('messages.tab1_panel_point1') }}</li>
                                <li><i class="bi bi-check-circle-fill text-brand me-2"></i>{{ __('messages.tab1_panel_point2') }}</li>
                            </ul>
                        </div>

                        <!-- Tab 2 -->
                        <div class="tab-pane fade" id="content-live" role="tabpanel">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="bg-brand text-white rounded-3 p-3"><i class="bi bi-calendar-event-fill fs-3"></i></div>
                                <div>
                                    <h4 class="fw-bold mb-0">{{ __('messages.tab2_panel_title') }}</h4>
                                    <small class="text-muted">{{ __('messages.tab2_panel_sub') }}</small>
                                </div>
                            </div>
                            <p class="text-muted">{{ __('messages.tab2_panel_desc') }}</p>
                            <ul class="list-unstyled d-flex flex-column gap-2 mb-0 small">
                                <li><i class="bi bi-check-circle-fill text-brand me-2"></i>{{ __('messages.tab2_panel_point1') }}</li>
                                <li><i class="bi bi-check-circle-fill text-brand me-2"></i>{{ __('messages.tab2_panel_point2') }}</li>
                            </ul>
                        </div>

                        <!-- Tab 3 -->
                        <div class="tab-pane fade" id="content-tugas" role="tabpanel">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="bg-brand text-white rounded-3 p-3"><i class="bi bi-award-fill fs-3"></i></div>
                                <div>
                                    <h4 class="fw-bold mb-0">{{ __('messages.tab3_panel_title') }}</h4>
                                    <small class="text-muted">{{ __('messages.tab3_panel_sub') }}</small>
                                </div>
                            </div>
                            <p class="text-muted">{{ __('messages.tab3_panel_desc') }}</p>
                            <ul class="list-unstyled d-flex flex-column gap-2 mb-0 small">
                                <li><i class="bi bi-check-circle-fill text-brand me-2"></i>{{ __('messages.tab3_panel_point1') }}</li>
                                <li><i class="bi bi-check-circle-fill text-brand me-2"></i>{{ __('messages.tab3_panel_point2') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. KATALOG PROGRAM BAHASA -->
    <section class="py-5" id="katalog">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-md-6 col-lg-5" data-aos="fade-up">
                    <div class="lang-card d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="lang-badge-code">EN</span>
                            <span class="badge bg-brand-soft text-brand">CEFR A1–C1</span>
                        </div>
                        <h5 class="fw-bold">{{ __('messages.prog_en_title') }}</h5>
                        <p class="text-muted small flex-grow-1">{{ __('messages.prog_en_desc') }}</p>
                        <a href="{{ $dashboardUrl }}" class="btn btn-outline-brand btn-sm fw-semibold w-100 mt-3 rounded-3">{{ __('messages.btn_enter_class') }}</a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-5" data-aos="fade-up" data-aos-delay="80">
                    <div class="lang-card d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="lang-badge-code">AR</span>
                            <span class="badge bg-brand-soft text-brand">{{ __('messages.prog_ar_badge') }}</span>
                        </div>
                        <h5 class="fw-bold">{{ __('messages.prog_ar_title') }}</h5>
                        <p class="text-muted small flex-grow-1">{{ __('messages.prog_ar_desc') }}</p>
                        <a href="{{ $dashboardUrl }}" class="btn btn-outline-brand btn-sm fw-semibold w-100 mt-3 rounded-3">{{ __('messages.btn_enter_class') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. CARA BERGABUNG DENGAN KODE KELAS -->
    <section class="py-5" id="panduan">
        <div class="container">
            <div class="rounded-4 p-4 p-md-5 text-white" style="background: linear-gradient(120deg, #047857 0%, #064e3b 100%);" data-aos="zoom-in">
                <div class="row align-items-center gy-4">
                    <div class="col-lg-8">
                        <span class="badge bg-warning text-dark fw-bold mb-2">{{ __('messages.join_badge') }}</span>
                        <h2 class="fw-extrabold mb-2">{{ __('messages.join_title') }}</h2>
                        <p class="mb-0 text-white-50">
                            {!! __('messages.join_desc_html') !!}
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        @auth
                            <a href="{{ $dashboardUrl }}" class="btn btn-light text-brand btn-lg fw-bold px-4 rounded-3 shadow-sm">
                                {{ __('messages.hero_cta_dashboard') }} <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-light text-brand btn-lg fw-bold px-4 rounded-3 shadow-sm">
                                {{ __('messages.join_cta') }} <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    const languages = [
        { name: @json(__('messages.lang_en_name')), greeting: 'Hello! Welcome to Jago Bahasa' },
        { name: @json(__('messages.lang_ar_name')), greeting: 'مرحباً! أهلاً بكم في Jago Bahasa' },
    ];

    let langIndex = 0;
    const langEl = document.getElementById('rotatingLang');
    const greetEl = document.getElementById('liveGreeting');

    if (langEl && greetEl) {
        setInterval(() => {
            langEl.style.opacity = 0;
            setTimeout(() => {
                langIndex = (langIndex + 1) % languages.length;
                langEl.textContent = languages[langIndex].name;
                greetEl.textContent = languages[langIndex].greeting;
                langEl.style.opacity = 1;
            }, 300);
        }, 3000);
    }

    const counters = document.querySelectorAll('.counter');
    const animateCounters = () => {
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            const count = +counter.innerText;
            const inc = Math.max(1, Math.ceil(target / 60));
            if (count < target) {
                counter.innerText = Math.min(count + inc, target);
                setTimeout(animateCounters, 25);
            } else {
                counter.innerText = target;
            }
        });
    };

    const observer = new IntersectionObserver(entries => {
        if (entries[0].isIntersecting) {
            animateCounters();
            observer.disconnect();
        }
    });

    const metricSection = document.getElementById('metrics');
    if (metricSection) observer.observe(metricSection);
</script>
@endpush