@extends('layouts.teacher')

@section('title', 'Kelas Diampu — JagoBahasa')
@section('page_title', 'Kelas yang Anda Ampu')
@section('page_subtitle', 'Kelola daftar kelas bahasa, kode akses murid, dan modul pembelajaran')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <span class="text-muted small">Total <strong>{{ $courses->count() }}</strong> kelas aktif</span>
        <a href="{{ route('teacher.courses.create') }}" class="btn btn-brand fw-semibold rounded-3 px-3">
            <i class="bi bi-plus-circle me-1"></i> Buat Kelas Baru
        </a>
    </div>

    <div class="row g-4">
        @forelse ($courses as $course)
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                        <span class="badge bg-brand-soft text-brand">{{ $course->language }}</span>
                        <span class="badge bg-light text-dark border font-monospace">
                            <i class="bi bi-key me-1"></i>{{ $course->class_code }}
                        </span>
                    </div>

                    <h5 class="fw-bold mb-1 mt-1">{{ $course->title }}</h5>
                    <small class="text-muted d-block mb-3">Tingkat: {{ $course->level ?? 'Umum' }}</small>
                    <p class="text-muted small mb-4">{{ Str::limit($course->description ?? 'Tidak ada deskripsi kelas.', 100) }}</p>

                    <div class="mt-auto pt-3 border-top">
                        <div class="d-flex justify-content-between small text-muted mb-3">
                            <span><i class="bi bi-people me-1"></i>{{ $course->students_count }} Murid</span>
                            <span><i class="bi bi-journal-text me-1"></i>{{ $course->lessons_count }} Bab</span>
                            <span><i class="bi bi-clipboard-check me-1"></i>{{ $course->assignments_count }} Tugas</span>
                        </div>
                        <a href="{{ route('teacher.courses.show', $course->id) }}" class="btn btn-brand w-100 fw-semibold rounded-3">
                            Kelola Materi &amp; Murid
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                    <div class="mx-auto bg-brand-soft text-brand rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                        <i class="bi bi-easel2 fs-2"></i>
                    </div>
                    <h5 class="fw-bold">Belum Ada Kelas Dibuat</h5>
                    <p class="text-muted small mx-auto mb-3" style="max-width: 420px;">
                        Mulai buat kelas Bahasa Inggris atau Bahasa Arab pertama Anda untuk mendapatkan kode kelas yang dapat dibagikan kepada murid.
                    </p>
                    <div>
                        <a href="{{ route('teacher.courses.create') }}" class="btn btn-brand fw-semibold px-4 rounded-3">
                            Buat Kelas Pertama
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
@endsection