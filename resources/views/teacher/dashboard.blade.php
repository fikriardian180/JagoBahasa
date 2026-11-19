@extends('layouts.teacher')

@section('title', 'Dashboard Pengajar — JagoBahasa')
@section('page_title', 'Dashboard Pengajar')
@section('page_subtitle', 'Ringkasan aktivitas kelas, peserta didik, dan evaluasi tugas')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-brand-soft text-brand rounded-3 p-3">
                        <i class="bi bi-easel2-fill fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Kelas Diampu</small>
                        <h4 class="fw-extrabold mb-0">{{ $courses->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                        <i class="bi bi-people-fill fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Total Murid</small>
                        <h4 class="fw-extrabold mb-0">{{ $totalStudents }}</h4>
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
                        <small class="text-muted d-block">Perlu Dinilai</small>
                        <h4 class="fw-extrabold mb-0">{{ $pendingGradingCount }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-info bg-opacity-10 text-info rounded-3 p-3">
                        <i class="bi bi-journal-text fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Total Modul</small>
                        <h4 class="fw-extrabold mb-0">{{ $totalLessons }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Kelas yang Anda Ampu</h6>
                    <a href="{{ route('teacher.courses.create') }}" class="btn btn-brand btn-sm rounded-3 fw-semibold">
                        <i class="bi bi-plus-lg me-1"></i>Kelas Baru
                    </a>
                </div>

                @forelse ($courses as $course)
                    <div class="p-3 rounded-3 border mb-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-brand-soft text-brand">{{ $course->language }}</span>
                                <span class="badge bg-light text-dark border font-monospace">Kode: {{ $course->class_code }}</span>
                            </div>
                            <h6 class="fw-bold mb-1">{{ $course->title }}</h6>
                            <small class="text-muted">
                                {{ $course->students_count }} Murid &bull; {{ $course->lessons_count }} Materi &bull; {{ $course->assignments_count }} Evaluasi
                            </small>
                        </div>
                        <a href="{{ route('teacher.courses.show', $course->id) }}" class="btn btn-outline-brand btn-sm fw-semibold px-3 rounded-3">
                            Kelola Kelas
                        </a>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted small">
                        <p class="mb-2">Anda belum membuat kelas bahasa apa pun.</p>
                        <a href="{{ route('teacher.courses.create') }}" class="btn btn-brand btn-sm rounded-3">Buat Kelas Pertama</a>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Tugas Menunggu Penilaian</h6>
                    <a href="{{ route('teacher.assignments.index') }}" class="small text-brand text-decoration-none fw-semibold">Semua</a>
                </div>

                @forelse ($ungradedSubmissions as $sub)
                    <div class="p-3 rounded-3 bg-light border mb-2 d-flex justify-content-between align-items-center gap-2">
                        <div class="overflow-hidden">
                            <div class="fw-bold small text-truncate">{{ $sub->student->name }}</div>
                            <div class="text-muted text-truncate" style="font-size: 0.78rem;">{{ $sub->assignment->title }}</div>
                        </div>
                        <a href="{{ route('teacher.assignments.submissions', $sub->assignment_id) }}" class="btn btn-warning text-dark btn-sm fw-semibold rounded-3 flex-shrink-0">
                            Nilai
                        </a>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted small">
                        <i class="bi bi-check2-all fs-4 d-block text-brand mb-1"></i>
                        Semua kiriman tugas sudah dinilai.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection