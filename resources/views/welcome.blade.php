@extends('layouts.app')

@section('title', 'JagoBahasa — Platform LMS Pembelajaran Bahasa Terpadu')

@section('fullwidth_content')
    <!-- 1. HERO SECTION: INTERAKTIF & BERKARAKTER LMS -->
    <section class="hero-lms" id="beranda">
        <div class="container">
            <div class="row align-items-center gy-5">
                <!-- Kolom Kiri -->
                <div class="col-lg-6" data-aos="fade-up">
                    <div class="greeting-pill mb-3">
                        <i class="bi bi-chat-dots-fill"></i>
                        <span id="liveGreeting">Hello! Selamat Datang di JagoBahasa</span>
                    </div>

                    <h1 class="display-5 fw-extrabold mb-3" style="line-height: 1.2;">
                        Belajar Bahasa <span class="dynamic-word" id="rotatingLang">Inggris</span> Jadi Lebih Terarah &amp; Terukur
                    </h1>

                    <p class="lead text-muted mb-4" style="font-size: 1.05rem; line-height: 1.75;">
                        Kelola materi kursus, kerjakan latihan kosakata &amp; tata bahasa, hingga ikuti sesi kelas daring bersama pengajar dalam satu ruang belajar digital.
                    </p>

                    <div class="d-flex flex-wrap gap-3">
                        @auth
                            <a href="{{ route('student.dashboard') }}" class="btn btn-brand btn-lg fw-semibold px-4 rounded-3">
                                Buka Dashboard Saya <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-brand btn-lg fw-semibold px-4 rounded-3">
                                Mulai Belajar Sekarang <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                            <a href="#metode" class="btn btn-outline-brand btn-lg fw-semibold px-4 rounded-3">
                                Eksplorasi Fitur
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Kolom Kanan: Simulasi Pratinjau UI LMS (Bukan Foto Statis) -->
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="150">
                    <div class="lms-preview-card">
                        <div class="lms-window-bar">
                            <div class="window-dots">
                                <span style="background: #ef4444;"></span>
                                <span style="background: #f59e0b;"></span>
                                <span style="background: #10b981;"></span>
                            </div>
                            <small class="text-muted fw-medium">Pratinjau Ruang Belajar Siswa</small>
                            <span class="badge bg-brand-soft text-brand">Aktif</span>
                        </div>

                        <div class="p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="fw-bold mb-0">Kursus Saya Semester Ini</h6>
                                    <small class="text-muted">Progres pembelajaran mingguan</small>
                                </div>
                                <span class="badge bg-brand rounded-pill px-3 py-2">3 Kelas Aktif</span>
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
                                        <div class="fw-bold small">Sesi Live: Pronunciation Drill</div>
                                        <small class="text-muted">Hari ini, 15:30 WIB &bull; Ruang Virtual A</small>
                                    </div>
                                </div>
                                <span class="badge bg-white text-dark border">Siap</span>
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
                        <h3 class="fw-extrabold text-brand mb-1"><span class="counter" data-target="2">0</span> Bahasa</h3>
                        <small class="text-muted">Kurikulum terstandar</small>
                    </div>
                </div>
                <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="80">
                    <div class="metric-box">
                        <h3 class="fw-extrabold text-brand mb-1"><span class="counter" data-target="50">0</span>+ Modul</h3>
                        <small class="text-muted">Materi teks, audio &amp; video</small>
                    </div>
                </div>
                <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="160">
                    <div class="metric-box">
                        <h3 class="fw-extrabold text-brand mb-1"><span class="counter" data-target="150">0</span>+ Kuis</h3>
                        <small class="text-muted">Evaluasi tata bahasa otomatis</small>
                    </div>
                </div>
                <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="240">
                    <div class="metric-box">
                        <h3 class="fw-extrabold text-brand mb-1"><span class="counter" data-target="24">0</span>/7 Akses</h3>
                        <small class="text-muted">Belajar fleksibel dari mana saja</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. METODE BELAJAR INTERAKTIF (TAB KLIK LANGSUNG) -->
    <section class="py-5 bg-white border-top border-bottom" id="metode">
        <div class="container py-lg-3">
            <div class="row align-items-center gy-4">
                <div class="col-lg-5" data-aos="fade-right">
                    <span class="badge bg-brand-soft text-brand fw-bold px-3 py-2 mb-2">Fitur Utama LMS</span>
                    <h2 class="fw-extrabold mb-3">Satu Sistem untuk Seluruh Alur Belajar</h2>
                    <p class="text-muted mb-4">Pilih pilar pembelajaran di bawah untuk melihat bagaimana JagoBahasa mendampingi proses belajar Anda.</p>

                    <div class="nav flex-column nav-pills method-nav gap-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <button class="nav-link active" id="tab-materi" data-bs-toggle="pill" data-bs-target="#content-materi" type="button" role="tab">
                            <div class="fw-bold"><i class="bi bi-journal-richtext me-2"></i>1. Modul Terstruktur &amp; Bertahap</div>
                            <small class="text-muted d-block mt-1">Materi disusun per bab lengkap dengan rangkuman kosakata.</small>
                        </button>

                        <button class="nav-link" id="tab-live" data-bs-toggle="pill" data-bs-target="#content-live" type="button" role="tab">
                            <div class="fw-bold"><i class="bi bi-camera-video me-2"></i>2. Kelas Online &amp; Kalender Jadwal</div>
                            <small class="text-muted d-block mt-1">Terhubung langsung dengan sesi tatap muka daring pengajar.</small>
                        </button>

                        <button class="nav-link" id="tab-tugas" data-bs-toggle="pill" data-bs-target="#content-tugas" type="button" role="tab">
                            <div class="fw-bold"><i class="bi bi-ui-checks me-2"></i>3. Tugas &amp; Penilaian Real-Time</div>
                            <small class="text-muted d-block mt-1">Pengumpulan tugas dan rekap nilai otomatis di satu halaman.</small>
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
                                    <h4 class="fw-bold mb-0">Akses Materi Kapan Saja</h4>
                                    <small class="text-muted">Terorganisir berdasarkan tingkat kemahiran</small>
                                </div>
                            </div>
                            <p class="text-muted">Setiap mata kursus dibagi ke dalam pertemuan mingguan sehingga peserta didik tidak kebingungan menentukan urutan belajar.</p>
                            <ul class="list-unstyled d-flex flex-column gap-2 mb-0 small">
                                <li><i class="bi bi-check-circle-fill text-brand me-2"></i>Dilengkapi lampiran PDF, audio pelafalan, dan catatan pengajar.</li>
                                <li><i class="bi bi-check-circle-fill text-brand me-2"></i>Penanda status selesai pada setiap bab yang telah dipelajari.</li>
                            </ul>
                        </div>

                        <!-- Tab 2 -->
                        <div class="tab-pane fade" id="content-live" role="tabpanel">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="bg-brand text-white rounded-3 p-3"><i class="bi bi-calendar-event-fill fs-3"></i></div>
                                <div>
                                    <h4 class="fw-bold mb-0">Jadwal Sinkron Tanpa Terlewat</h4>
                                    <small class="text-muted">Terintegrasi dengan menu Kelas Online &amp; Kalender</small>
                                </div>
                            </div>
                            <p class="text-muted">Pengajar dapat menjadwalkan sesi praktik percakapan (*speaking*) dan tautan pertemuan langsung muncul di dashboard siswa.</p>
                            <ul class="list-unstyled d-flex flex-column gap-2 mb-0 small">
                                <li><i class="bi bi-check-circle-fill text-brand me-2"></i>Pengingat jadwal otomatis di halaman Kalender Akademik.</li>
                                <li><i class="bi bi-check-circle-fill text-brand me-2"></i>Rekap presensi kehadiran terintegrasi dalam sistem.</li>
                            </ul>
                        </div>

                        <!-- Tab 3 -->
                        <div class="tab-pane fade" id="content-tugas" role="tabpanel">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="bg-brand text-white rounded-3 p-3"><i class="bi bi-award-fill fs-3"></i></div>
                                <div>
                                    <h4 class="fw-bold mb-0">Evaluasi &amp; Umpan Balik Cepat</h4>
                                    <small class="text-muted">Pantau perkembangan kemampuan bahasa Anda</small>
                                </div>
                            </div>
                            <p class="text-muted">Kumpulkan tugas menulis (*writing*) maupun rekaman pelafalan langsung melalui portal tugas dan terima koreksi dari pengajar.</p>
                            <ul class="list-unstyled d-flex flex-column gap-2 mb-0 small">
                                <li><i class="bi bi-check-circle-fill text-brand me-2"></i>Status pengumpulan tugas jelas (Terkumpul, Dinilai, atau Tenggat).</li>
                                <li><i class="bi bi-check-circle-fill text-brand me-2"></i>Grafik persentase penyelesaian materi di halaman Dashboard.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. KATALOG PROGRAM BAHASA -->
    <section class="py-5" id="katalog">
        <div class="row g-4 justify-content-center">
                <div class="col-md-6 col-lg-5" data-aos="fade-up">
                    <div class="lang-card d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="lang-badge-code">EN</span>
                            <span class="badge bg-brand-soft text-brand">CEFR A1–C1</span>
                        </div>
                        <h5 class="fw-bold">Bahasa Inggris</h5>
                        <p class="text-muted small flex-grow-1">Grammar, academic writing, listening comprehension, dan public speaking.</p>
                        <a href="{{ auth()->check() ? route('student.courses.index') : route('login') }}" class="btn btn-outline-brand btn-sm fw-semibold w-100 mt-3 rounded-3">Masuk Kelas</a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-5" data-aos="fade-up" data-aos-delay="80">
                    <div class="lang-card d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="lang-badge-code">AR</span>
                            <span class="badge bg-brand-soft text-brand">Dasar–Mahir</span>
                        </div>
                        <h5 class="fw-bold">Bahasa Arab</h5>
                        <p class="text-muted small flex-grow-1">Kaidah nahwu &amp; sharaf, percakapan aktif (kalam), serta pemahaman teks.</p>
                        <a href="{{ auth()->check() ? route('student.courses.index') : route('login') }}" class="btn btn-outline-brand btn-sm fw-semibold w-100 mt-3 rounded-3">Masuk Kelas</a>
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
                        <span class="badge bg-warning text-dark fw-bold mb-2">Akses Instan</span>
                        <h2 class="fw-extrabold mb-2">Sudah Punya Kode Kelas dari Pengajar?</h2>
                        <p class="mb-0 text-white-50">
                            Masuk ke akun JagoBahasa Anda, klik tombol <strong>Gabung Kode</strong> di bagian atas, dan langsung mulai pelajari modul kelas Anda.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        @auth
                            @php
                                $dashUrl = match(Auth::user()->role) {
                                    'admin' => url('/admin/dashboard'),
                                    'teacher' => url('/teacher/dashboard'),
                                    default => route('student.dashboard'),
                                };
                            @endphp
                            <a href="{{ $dashUrl }}" class="btn btn-brand btn-lg fw-semibold px-4 rounded-3">
                                Buka Dashboard Saya <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-brand btn-lg fw-semibold px-4 rounded-3">
                                Mulai Belajar Sekarang <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                            <a href="#metode" class="btn btn-outline-brand btn-lg fw-semibold px-4 rounded-3">
                                Eksplorasi Fitur
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
    // 1. Animasi Pergantian Bahasa & Sapaan di Hero Section
    const languages = [
        { name: 'Inggris', greeting: 'Hello! Welcome to Jago Bahasa' },
        { name: 'Arab', greeting: 'مرحباً! أهلاً بكم في Jago Bahasa' },
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

    // 2. Animasi Counter Angka Metrik
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