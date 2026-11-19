@extends('layouts.student')

@section('title', ($course->title ?? 'Detail Kursus') . ' — JagoBahasa')
@section('page_title', $course->title ?? 'Academic English & Speaking')
@section('page_subtitle', 'Kode Kelas: ' . ($course->class_code ?? 'JGB-EN01') . ' • Pengajar: ' . ($course->teacher->name ?? 'Pengajar'))

@section('content')
    <!-- Tombol Kembali -->
    <div class="mb-3">
        <a href="{{ route('student.courses.index') }}" class="text-decoration-none text-muted small fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Kursus
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- KOLOM KIRI: DAFTAR MODUL / PERTEMUAN -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h5 class="fw-bold mb-2">Deskripsi Kursus</h5>
                <p class="text-muted small mb-0">
                    {{ $course->description ?? 'Pelajari struktur tata bahasa, kosakata akademik, dan keterampilan komunikasi lisan secara bertahap.' }}
                </p>
            </div>

            <h5 class="fw-bold mb-3">Silabus &amp; Materi Pembelajaran</h5>

            <div class="accordion d-flex flex-column gap-3" id="lessonsAccordion">
                @forelse ($course->lessons ?? [] as $index => $lesson)
                    @php
                        // Cek apakah murid sudah menyelesaikan modul ini di tabel pivot
                        $isCompleted = $lesson->is_completed ?? false;
                    @endphp

                    <div class="accordion-item border-0 shadow-sm rounded-4 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }} fw-bold bg-white py-3" type="button" data-bs-toggle="collapse" data-bs-target="#lessonCollapse{{ $lesson->id }}">
                                <div class="d-flex align-items-center gap-3 w-100 pe-3">
                                    @if ($isCompleted)
                                        <span class="badge bg-success rounded-circle p-2"><i class="bi bi-check-lg"></i></span>
                                    @else
                                        <span class="badge bg-brand-soft text-brand rounded-circle px-2 py-1">{{ $index + 1 }}</span>
                                    @endif
                                    <span class="flex-grow-1">{{ $lesson->title }}</span>
                                    <small class="text-muted fw-normal d-none sm-inline">{{ $lesson->duration ?? '30 Menit' }}</small>
                                </div>
                            </button>
                        </h2>
                        <div id="lessonCollapse{{ $lesson->id }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" data-bs-parent="#lessonsAccordion">
                            <div class="accordion-body border-top bg-light">
                                <div class="text-dark small mb-3" style="line-height: 1.7;">
                                    {!! nl2br(e($lesson->content)) !!}
                                </div>

                                <!-- Lampiran File / Video jika ada -->
                                @if (!empty($lesson->attachment_url))
                                    <div class="p-3 bg-white rounded-3 border mb-3 d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2 small fw-semibold">
                                            <i class="bi bi-file-earmark-pdf-fill text-danger fs-5"></i>
                                            <span>Lampiran Materi Pembelajaran</span>
                                        </div>
                                        <a href="{{ $lesson->attachment_url }}" target="_blank" class="btn btn-outline-brand btn-sm rounded-3">
                                            Buka Materi <i class="bi bi-box-arrow-up-right ms-1"></i>
                                        </a>
                                    </div>
                                @endif

                                <!-- Form Tandai Selesai ke Backend -->
                                <form action="{{ route('student.lessons.complete', $lesson->id) }}" method="POST" class="text-end">
                                    @csrf
                                    <button type="submit" class="btn {{ $isCompleted ? 'btn-success' : 'btn-brand' }} btn-sm fw-semibold px-3 rounded-3">
                                        @if ($isCompleted)
                                            <i class="bi bi-check-circle-fill me-1"></i> Sudah Dipelajari
                                        @else
                                            <i class="bi bi-check2-circle me-1"></i> Tandai Selesai
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center text-muted small">
                        Pengajar belum menambahkan materi pada kelas ini.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- KOLOM KANAN: RINGKASAN PROGRES & TUGAS KELAS -->
        <div class="col-lg-4">
            <!-- Kartu Progres -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h6 class="fw-bold mb-3">Progres Kelas Anda</h6>
                @php
                    $total = ($course->lessons ?? collect())->count();
                    $done = ($course->lessons ?? collect())->where('is_completed', true)->count();
                    $pct = $total > 0 ? round(($done / $total) * 100) : 0;
                @endphp
                <div class="d-flex justify-content-between small fw-bold mb-1">
                    <span>Penyelesaian</span>
                    <span class="text-brand">{{ $pct }}%</span>
                </div>
                <div class="progress mb-2" style="height: 9px;">
                    <div class="progress-bar bg-brand" style="width: {{ $pct }}%;"></div>
                </div>
                <small class="text-muted">{{ $done }} dari {{ $total }} bab telah diselesaikan</small>
            </div>

            <!-- Kartu Tugas Terkait Kelas -->
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h6 class="fw-bold mb-3">Tugas &amp; Evaluasi Kelas</h6>
                @forelse ($course->assignments ?? [] as $assignment)
                    <div class="p-3 rounded-3 border bg-light mb-2">
                        <div class="fw-bold small">{{ $assignment->title }}</div>
                        <small class="text-muted d-block mb-2">
                            <i class="bi bi-clock me-1"></i>Tenggat: {{ \Carbon\Carbon::parse($assignment->due_date)->format('d M Y, H:i') }}
                        </small>
                        <a href="{{ route('student.assignments.show', $assignment->id) }}" class="btn btn-outline-brand btn-sm w-100 rounded-3 fw-semibold">Kerjakan Tugas</a>
                    </div>
                @empty
                    <p class="text-muted small mb-0">Belum ada tugas aktif untuk kelas ini.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection