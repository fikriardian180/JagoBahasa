@extends('layouts.student')

@section('title', 'Dashboard Murid — JagoBahasa')
@section('page_title', 'Dashboard Belajar')
@section('page_subtitle', 'Selamat datang kembali, ' . (Auth::user()->name ?? 'Murid') . '! Lanjutkan progres belajar Anda.')

@section('content')
    <!-- 1. RINGKASAN STATISTIK BELAJAR MURID -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-brand-soft text-brand rounded-3 p-3">
                        <i class="bi bi-journal-bookmark-fill fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Kursus Aktif</small>
                        <h4 class="fw-extrabold mb-0">{{ $activeCoursesCount ?? 0 }} Kelas</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Tugas Tertunda</small>
                        <h4 class="fw-extrabold mb-0">{{ $pendingAssignmentsCount ?? 0 }} Tugas</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-info bg-opacity-10 text-info rounded-3 p-3">
                        <i class="bi bi-check2-all fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Modul Selesai</small>
                        <h4 class="fw-extrabold mb-0">{{ $completedModules ?? 0 }} / {{ $totalModules ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
                        <i class="bi bi-trophy-fill fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Rata-Rata Nilai</small>
                        <h4 class="fw-extrabold mb-0">{{ $averageGrade ?? '-' }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. KURSUS SAYA & JADWAL TERDEKAT -->
    <div class="row g-4">
        <!-- Kolom Kiri: Daftar Kelas Aktif -->
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Lanjutkan Belajar</h5>
                <a href="{{ route('student.courses.index') }}" class="text-brand small fw-semibold text-decoration-none">
                    Lihat Semua Kelas <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="row g-3">
                @forelse ($enrolledCourses->take(4) as $course)
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 p-4 h-100 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-brand-soft text-brand">{{ $course->language }}</span>
                                <small class="text-muted">Bab {{ $course->completed_lessons_count }} dari {{ $course->lessons_count }}</small>
                            </div>
                            <h6 class="fw-bold mb-1">{{ $course->title }}</h6>
                            <p class="text-muted small mb-3">Pengajar: {{ $course->teacher->name ?? 'Pengajar JagoBahasa' }}</p>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between small fw-semibold mb-1">
                                    <span>Progres</span>
                                    <span class="text-brand">{{ $course->progress_percent }}%</span>
                                </div>
                                <div class="progress mb-3" style="height: 7px;">
                                    <div class="progress-bar bg-brand" style="width: {{ $course->progress_percent }}%;"></div>
                                </div>
                                <a href="{{ route('student.courses.show', $course->id) }}" class="btn {{ $course->progress_percent > 0 ? 'btn-brand' : 'btn-outline-brand' }} btn-sm w-100 rounded-3 fw-semibold">
                                    {{ $course->progress_percent > 0 ? 'Lanjutkan Materi' : 'Mulai Belajar' }}
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
                            <p class="text-muted small mb-3">Anda belum bergabung di kelas mana pun. Gunakan kode kelas dari pengajar untuk memulai.</p>
                            <div>
                                <button type="button" class="btn btn-brand btn-sm px-4 rounded-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#joinCourseModal">
                                    <i class="bi bi-plus-circle me-1"></i> Gabung Kode Kelas
                                </button>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Kolom Kanan: Agenda & Tenggat Tugas -->
        <div class="col-lg-4">
            <h5 class="fw-bold mb-3">Agenda &amp; Tenggat</h5>
            <div class="card border-0 shadow-sm rounded-4 p-3">
                @if ($nextLiveClass)
                    <div class="p-3 rounded-3 bg-brand-soft border border-success border-opacity-25 mb-3">
                        <div class="d-flex align-items-center gap-2 text-brand fw-bold small mb-1">
                            <i class="bi bi-camera-video-fill"></i>
                            <span>Kelas Online Terdekat</span>
                        </div>
                        <div class="fw-bold small">{{ $nextLiveClass->title }}</div>
                        <small class="text-muted d-block mb-2">
                            {{ \Carbon\Carbon::parse($nextLiveClass->scheduled_at)->format('d M Y, H:i') }} WIB
                        </small>
                        <a href="{{ $nextLiveClass->meeting_url ?? '#' }}" target="_blank" class="btn btn-brand btn-sm rounded-3 w-100 fw-semibold">
                            Masuk Ruang Kelas
                        </a>
                    </div>
                @else
                    <div class="p-3 rounded-3 bg-light border mb-3 text-center">
                        <i class="bi bi-calendar-check text-muted fs-4 d-block mb-1"></i>
                        <small class="text-muted">Tidak ada jadwal kelas online dalam waktu dekat.</small>
                    </div>
                @endif

                <div class="border-top pt-3">
                    <small class="text-muted fw-bold d-block mb-2">TUGAS MENDATANG</small>
                    @forelse ($upcomingAssignments as $task)
                        <div class="d-flex align-items-start gap-2 mb-3">
                            <i class="bi bi-file-earmark-text text-warning fs-5"></i>
                            <div>
                                <div class="fw-semibold small">{{ $task->title }}</div>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    {{ $task->course_language }} &bull; {{ \Carbon\Carbon::parse($task->due_date)->format('d M, H:i') }}
                                </small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">Semua tugas sudah selesai atau belum ada tugas baru.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection