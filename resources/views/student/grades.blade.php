@extends('layouts.student')

@section('title', __('messages.grades_page_title') . ' — JagoBahasa')
@section('page_title', __('messages.grades_header_title'))
@section('page_subtitle', __('messages.grades_header_sub'))

@section('content')
    <div class="card border-0 shadow-sm rounded-4 p-3 p-sm-4 mb-3 mb-sm-4">
        <h5 class="fw-bold mb-2">{{ __('messages.grades_card_title') }}</h5>
        <p class="text-muted small mb-0" style="line-height: 1.65;">
            {{ __('messages.grades_card_desc') }}
        </p>
    </div>

    @php
        $user = Auth::user();
        $submissions = \App\Models\AssignmentSubmission::with('assignment.course')
            ->where('user_id', $user->id)
            ->latest('submitted_at')
            ->get();
    @endphp

    <!-- TAMPILAN DESKTOP / TABLET (>= 768px): TABEL -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden d-none d-md-block">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small">
                    <tr>
                        <th class="py-3 px-4">{{ __('messages.grades_col_task') }}</th>
                        <th class="py-3">{{ __('messages.grades_col_course') }}</th>
                        <th class="py-3">{{ __('messages.grades_col_submitted_at') }}</th>
                        <th class="py-3 text-center">{{ __('messages.grades_col_score') }}</th>
                        <th class="py-3 px-4 text-end">{{ __('messages.grades_col_action') }}</th>
                    </tr>
                </thead>
                <tbody class="small">
                    @forelse ($submissions as $sub)
                        <tr>
                            <td class="px-4 fw-bold">{{ $sub->assignment->title ?? __('messages.grades_task_fallback') }}</td>
                            <td>{{ $sub->assignment->course->title ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($sub->submitted_at)->format('d M Y, H:i') }}</td>
                            <td class="text-center">
                                @if ($sub->score !== null)
                                    <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-2 fs-6">
                                        {{ number_format($sub->score, 0) }}
                                    </span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-dark px-3 py-2">{{ __('messages.grades_pending_badge') }}</span>
                                @endif
                            </td>
                            <td class="px-4 text-end">
                                <a href="{{ route('student.assignments.show', $sub->assignment_id) }}" class="btn btn-sm btn-outline-brand rounded-3">
                                    {{ __('messages.grades_btn_detail') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                {{ __('messages.grades_empty_state') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAMPILAN SMARTPHONE (< 768px): KARTU RINGKAS TANPA SCROLL HORIZONTAL -->
    <div class="d-md-none d-flex flex-column gap-3">
        @forelse ($submissions as $sub)
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                    <div class="min-w-0">
                        <h6 class="fw-bold mb-1 lh-sm">{{ $sub->assignment->title ?? __('messages.grades_task_fallback') }}</h6>
                        <small class="text-muted d-block text-truncate">
                            <i class="bi bi-journal-bookmark me-1"></i>{{ $sub->assignment->course->title ?? '-' }}
                        </small>
                    </div>
                    <div class="flex-shrink-0">
                        @if ($sub->score !== null)
                            <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-2 fs-6">
                                {{ number_format($sub->score, 0) }}
                            </span>
                        @else
                            <span class="badge bg-warning bg-opacity-10 text-dark px-2 py-1 small">{{ __('messages.grades_pending_badge') }}</span>
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-2 mt-2 border-top gap-2">
                    <small class="text-muted" style="font-size: 0.78rem;">
                        <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($sub->submitted_at)->format('d M Y, H:i') }}
                    </small>
                    <a href="{{ route('student.assignments.show', $sub->assignment_id) }}" class="btn btn-sm btn-outline-brand fw-semibold px-3 rounded-3">
                        {{ __('messages.grades_btn_detail') }}
                    </a>
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center text-muted small">
                {{ __('messages.grades_empty_state') }}
            </div>
        @endforelse
    </div>
@endsection