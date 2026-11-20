@extends('layouts.student')

@section('title', __('messages.student_dash_title') . ' — JagoBahasa')
@section('page_title', __('messages.student_dash_title'))
@section('page_subtitle', __('messages.student_welcome_back', ['name' => Auth::user()->name ?? __('messages.student_role_badge')]))

@section('content')
    <!-- 1. RINGKASAN STATISTIK BELAJAR MURID -->
    <div class="row g-2 g-sm-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-2 p-sm-3 h-100 overflow-hidden">
                <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-2 gap-sm-3">
                    <div class="bg-brand-soft text-brand rounded-3 p-2 p-sm-3 flex-shrink-0">
                        <i class="bi bi-journal-bookmark-fill fs-5 fs-sm-4"></i>
                    </div>
                    <div class="min-w-0 w-100">
                        <small class="text-muted d-block text-truncate" style="font-size: 0.76rem;">{{ __('messages.student_active_courses') }}</small>
                        <h4 class="fw-extrabold mb-0" style="font-size: clamp(1.05rem, 4.5vw, 1.45rem);">{{ $activeCoursesCount ?? 0 }} {{ __('messages.unit_classes') }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-2 p-sm-3 h-100 overflow-hidden">
                <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-2 gap-sm-3">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-2 p-sm-3 flex-shrink-0">
                        <i class="bi bi-hourglass-split fs-5 fs-sm-4"></i>
                    </div>
                    <div class="min-w-0 w-100">
                        <small class="text-muted d-block text-truncate" style="font-size: 0.76rem;">{{ __('messages.student_pending_tasks') }}</small>
                        <h4 class="fw-extrabold mb-0" style="font-size: clamp(1.05rem, 4.5vw, 1.45rem);">{{ $pendingAssignmentsCount ?? 0 }} {{ __('messages.unit_tasks') }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-2 p-sm-3 h-100 overflow-hidden">
                <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-2 gap-sm-3">
                    <div class="bg-info bg-opacity-10 text-info rounded-3 p-2 p-sm-3 flex-shrink-0">
                        <i class="bi bi-check2-all fs-5 fs-sm-4"></i>
                    </div>
                    <div class="min-w-0 w-100">
                        <small class="text-muted d-block text-truncate" style="font-size: 0.76rem;">{{ __('messages.student_completed_lessons') }}</small>
                        <h4 class="fw-extrabold mb-0" style="font-size: clamp(1.05rem, 4.5vw, 1.45rem);">{{ $completedModules ?? 0 }} / {{ $totalModules ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-2 p-sm-3 h-100 overflow-hidden">
                <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-2 gap-sm-3">
                    <div class="bg-success bg-opacity-10 text-success rounded-3 p-2 p-sm-3 flex-shrink-0">
                        <i class="bi bi-trophy-fill fs-5 fs-sm-4"></i>
                    </div>
                    <div class="min-w-0 w-100">
                        <small class="text-muted d-block text-truncate" style="font-size: 0.76rem;">{{ __('messages.student_avg_score') }}</small>
                        <h4 class="fw-extrabold mb-0" style="font-size: clamp(1.05rem, 4.5vw, 1.45rem);">{{ $averageGrade ?? '-' }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. KURSUS SAYA & JADWAL TERDEKAT -->
    <div class="row g-3 g-lg-4">
        <!-- Kolom Kiri: Daftar Kelas Aktif -->
        <div class="col-lg-8">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <h5 class="fw-bold mb-0">{{ __('messages.student_continue_learning') }}</h5>
                <a href="{{ route('student.courses.index') }}" class="text-brand small fw-semibold text-decoration-none">
                    {{ __('messages.student_view_all_classes') }} <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="row g-3">
                @forelse ($enrolledCourses->take(4) as $course)
                    <div class="col-12 col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 p-3 p-sm-4 h-100 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                <span class="badge bg-brand-soft text-brand">{{ $course->language }}</span>
                                <small class="text-muted text-end">{{ __('messages.student_chapter_progress', ['done' => $course->completed_lessons_count, 'total' => $course->lessons_count]) }}</small>
                            </div>
                            <h6 class="fw-bold mb-1">{{ $course->title }}</h6>
                            <p class="text-muted small mb-3">{{ __('messages.student_instructor_label') }}: {{ $course->teacher->name ?? __('messages.student_default_instructor') }}</p>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between small fw-semibold mb-1">
                                    <span>{{ __('messages.student_progress_label') }}</span>
                                    <span class="text-brand">{{ $course->progress_percent }}%</span>
                                </div>
                                <div class="progress mb-3" style="height: 7px;">
                                    <div class="progress-bar bg-brand" style="width: {{ $course->progress_percent }}%;"></div>
                                </div>
                                <a href="{{ route('student.courses.show', $course->id) }}" class="btn {{ $course->progress_percent > 0 ? 'btn-brand' : 'btn-outline-brand' }} btn-sm w-100 rounded-3 fw-semibold py-2">
                                    {{ $course->progress_percent > 0 ? __('messages.student_btn_continue_lesson') : __('messages.student_btn_start_lesson') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
                            <p class="text-muted small mb-3">{{ __('messages.student_no_classes_desc') }}</p>
                            <div class="d-grid d-sm-block">
                                <button type="button" class="btn btn-brand btn-sm px-4 py-2 rounded-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#joinCourseModal">
                                    <i class="bi bi-plus-circle me-1"></i> {{ __('messages.student_btn_join_code') }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Kolom Kanan: Agenda & Tenggat Tugas -->
        <div class="col-lg-4">
            <h5 class="fw-bold mb-3">{{ __('messages.student_agenda_deadlines') }}</h5>
            <div class="card border-0 shadow-sm rounded-4 p-3">
                @if ($nextLiveClass)
                    <div class="p-3 rounded-3 bg-brand-soft border border-success border-opacity-25 mb-3">
                        <div class="d-flex align-items-center gap-2 text-brand fw-bold small mb-1">
                            <i class="bi bi-camera-video-fill"></i>
                            <span>{{ __('messages.student_next_live_class') }}</span>
                        </div>
                        <div class="fw-bold small">{{ $nextLiveClass->title }}</div>
                        <small class="text-muted d-block mb-2">
                            {{ \Carbon\Carbon::parse($nextLiveClass->scheduled_at)->format('d M Y, H:i') }} WIB
                        </small>
                        <a href="{{ $nextLiveClass->meeting_url ?? '#' }}" target="_blank" class="btn btn-brand btn-sm rounded-3 w-100 fw-semibold py-2">
                            {{ __('messages.student_btn_enter_room') }}
                        </a>
                    </div>
                @else
                    <div class="p-3 rounded-3 bg-light border mb-3 text-center">
                        <i class="bi bi-calendar-check text-muted fs-4 d-block mb-1"></i>
                        <small class="text-muted">{{ __('messages.student_no_live_class') }}</small>
                    </div>
                @endif

                <div class="border-top pt-3">
                    <small class="text-muted fw-bold d-block mb-2">{{ __('messages.student_upcoming_tasks_heading') }}</small>
                    @forelse ($upcomingAssignments as $task)
                        <div class="d-flex align-items-start gap-2 mb-3">
                            <i class="bi bi-file-earmark-text text-warning fs-5 flex-shrink-0"></i>
                            <div class="min-w-0 w-100">
                                <div class="fw-semibold small text-truncate">{{ $task->title }}</div>
                                <small class="text-muted d-block" style="font-size: 0.75rem;">
                                    {{ $task->course_language }} &bull; {{ \Carbon\Carbon::parse($task->due_date)->format('d M, H:i') }}
                                </small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">{{ __('messages.student_no_upcoming_tasks') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection