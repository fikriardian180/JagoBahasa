@extends('layouts.student')

@section('title', __('messages.student_my_courses') . ' — JagoBahasa')
@section('page_title', __('messages.student_my_courses'))
@section('page_subtitle', __('messages.courses_header_sub'))

@section('content')
    <!-- Notifikasi Sukses / Error saat Gabung Kode Kelas -->
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

    <!-- BAR FILTER, PENCARIAN & GABUNG KODE -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4">
        <div class="row g-2 g-sm-3 align-items-center">
            <div class="col-12 col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" id="searchCourseInput" class="form-control bg-light border-start-0" placeholder="{{ __('messages.courses_search_placeholder') }}">
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <select id="filterLanguageSelect" class="form-select bg-light">
                    <option value="all">{{ __('messages.courses_filter_all') }}</option>
                    <option value="Bahasa Inggris">{{ __('messages.prog_en_title') }}</option>
                    <option value="Bahasa Arab">{{ __('messages.prog_ar_title') }}</option>
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-3 text-md-end">
                <button type="button" class="btn btn-brand w-100 fw-semibold rounded-3 py-2" data-bs-toggle="modal" data-bs-target="#joinCourseModal">
                    <i class="bi bi-plus-lg me-1"></i> {{ __('messages.courses_btn_join_new') }}
                </button>
            </div>
        </div>
    </div>

    <!-- GRID DAFTAR KURSUS -->
    <div class="row g-3 g-sm-4" id="courseGrid">
        @forelse ($courses ?? [] as $course)
            @php
                // Menghitung progres otomatis dari backend jika relasi tersedia
                $totalLessons = $course->lessons_count ?? 10;
                $completedLessons = $course->completed_lessons_count ?? 0;
                $progress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
            @endphp

            <div class="col-12 col-md-6 col-xl-4 course-card-item"
                 data-title="{{ strtolower($course->title . ' ' . $course->class_code) }}"
                 data-language="{{ $course->language }}">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden d-flex flex-column">
                    <!-- Banner Header Kartu -->
                    <div class="p-3 p-sm-4 text-white position-relative" style="background: linear-gradient(135deg, #047857 0%, #065f46 100%);">
                        <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                            <span class="badge bg-white text-brand fw-bold">{{ $course->language }}</span>
                            <span class="badge bg-dark bg-opacity-25 font-monospace">{{ $course->class_code }}</span>
                        </div>
                        <h5 class="fw-bold mb-1 fs-6 fs-sm-5">{{ $course->title }}</h5>
                        <small class="text-white-50 d-block text-truncate">
                            <i class="bi bi-person-badge me-1"></i>{{ $course->teacher->name ?? __('messages.student_default_instructor') }}
                        </small>
                    </div>

                    <!-- Isi Kartu & Progres -->
                    <div class="card-body p-3 p-sm-4 d-flex flex-column">
                        <p class="text-muted small mb-3 mb-sm-4 flex-grow-1">
                            {{ Str::limit($course->description, 100) }}
                        </p>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between small fw-semibold mb-1 gap-2">
                                <span class="text-muted text-truncate">{{ __('messages.courses_progress_chapters', ['done' => $completedLessons, 'total' => $totalLessons]) }}</span>
                                <span class="text-brand flex-shrink-0">{{ $progress }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-brand" role="progressbar" style="width: {{ $progress }}%;"></div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('student.courses.show', $course->id) }}" class="btn btn-brand fw-semibold w-100 rounded-3 py-2">
                                <i class="bi bi-play-circle me-1"></i> {{ __('messages.btn_enter_class') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <!-- Tampilan Jika Murid Belum Bergabung di Kelas Mana Pun -->
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-sm-5 text-center">
                    <div class="mx-auto bg-brand-soft text-brand rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                        <i class="bi bi-journal-x fs-2"></i>
                    </div>
                    <h5 class="fw-bold">{{ __('messages.courses_empty_title') }}</h5>
                    <p class="text-muted small mx-auto mb-4" style="max-width: 420px;">
                        {{ __('messages.courses_empty_desc') }}
                    </p>
                    <div class="d-grid d-sm-block">
                        <button type="button" class="btn btn-brand px-4 py-2 fw-semibold rounded-3" data-bs-toggle="modal" data-bs-target="#joinCourseModal">
                            <i class="bi bi-plus-circle-fill me-1"></i> {{ __('messages.courses_btn_join_now') }}
                        </button>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
@endsection

@push('scripts')
<script>
    // Filter & Pencarian Real-Time di Halaman Kursus
    const searchInput = document.getElementById('searchCourseInput');
    const langSelect = document.getElementById('filterLanguageSelect');
    const courseItems = document.querySelectorAll('.course-card-item');

    const filterCourses = () => {
        const keyword = searchInput.value.toLowerCase();
        const selectedLang = langSelect.value;

        courseItems.forEach(item => {
            const title = item.getAttribute('data-title');
            const lang = item.getAttribute('data-language');
            const matchText = title.includes(keyword);
            const matchLang = (selectedLang === 'all' || lang === selectedLang);

            item.style.display = (matchText && matchLang) ? '' : 'none';
        });
    };

    searchInput?.addEventListener('input', filterCourses);
    langSelect?.addEventListener('change', filterCourses);
</script>
@endpush