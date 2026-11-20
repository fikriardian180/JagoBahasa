@extends('layouts.student')

@section('title', $assignment->title . ' — JagoBahasa')
@section('page_title', $assignment->title)
@section('page_subtitle', $assignment->course->title . ' • ' . __('messages.course_detail_due_label') . ': ' . $assignment->due_date->format('d M Y, H:i') . ' WIB')

@section('content')
    <div class="mb-3">
        <a href="{{ route('student.assignments.index') }}" class="text-decoration-none text-muted small fw-semibold d-inline-flex align-items-center py-1">
            <i class="bi bi-arrow-left me-1"></i> {{ __('messages.assignment_show_back') }}
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        </div>
    @endif

    <!-- INSTRUKSI EVALUASI -->
    <div class="card border-0 shadow-sm rounded-4 p-3 p-sm-4 mb-4">
        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
            <span class="badge {{ $assignment->type === 'quiz' ? 'bg-info text-dark' : 'bg-warning text-dark' }}">
                {{ $assignment->type === 'quiz' ? __('messages.assignment_show_type_quiz') : __('messages.assignment_show_type_task') }}
            </span>
            <span class="badge bg-brand-soft text-brand">{{ $assignment->course->language }}</span>
        </div>
        <h5 class="fw-bold mb-2">{{ __('messages.assignment_show_instructions_title') }}</h5>
        <p class="text-muted small mb-0" style="line-height: 1.7;">
            {!! nl2br(e($assignment->instructions ?? __('messages.assignment_show_instructions_fallback'))) !!}
        </p>
    </div>

    <!-- JIKA SUDAH DIKUMPULKAN: TAMPILKAN HASIL & NILAI -->
    @if ($submission)
        <div class="card border-0 shadow-sm rounded-4 p-3 p-sm-4 border-start border-4 border-success">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3 mb-3 pb-3 border-bottom">
                <div>
                    <span class="badge bg-success mb-1"><i class="bi bi-check-circle-fill me-1"></i>{{ __('messages.assignment_show_submitted_badge') }}</span>
                    <div class="small text-muted">{{ __('messages.assignment_show_submitted_at', ['time' => \Carbon\Carbon::parse($submission->submitted_at)->format('d M Y, H:i')]) }}</div>
                </div>
                <div class="bg-brand-soft px-3 px-sm-4 py-2 rounded-3 text-center">
                    <small class="text-muted d-block">{{ __('messages.assignment_show_your_score') }}</small>
                    <h3 class="fw-extrabold text-brand mb-0">
                        {{ $submission->score !== null ? number_format($submission->score, 0) . ' / 100' : __('messages.assignment_show_pending_grade') }}
                    </h3>
                </div>
            </div>

            @if ($assignment->type === 'task')
                <h6 class="fw-bold small">{{ __('messages.assignment_show_your_text_answer') }}:</h6>
                <div class="p-3 bg-light rounded-3 small mb-3" style="word-break: break-word;">{!! nl2br(e($submission->answer_text)) !!}</div>

                @if ($submission->attachment_url)
                    <div class="small mb-3">
                        <strong class="d-block d-sm-inline">{{ __('messages.assignment_show_attachment_link') }}:</strong>
                        <a href="{{ $submission->attachment_url }}" target="_blank" class="text-brand ms-sm-1 text-break">{{ $submission->attachment_url }}</a>
                    </div>
                @endif
            @else
                @php $userAnswers = json_decode($submission->answer_text, true) ?? []; @endphp
                <h6 class="fw-bold small mb-3">{{ __('messages.assignment_show_quiz_review') }}:</h6>
                <div class="d-flex flex-column gap-3">
                    @foreach ($assignment->questions as $idx => $q)
                        @php
                            $chosen = $userAnswers[$q->id] ?? '-';
                            $isCorrect = strtolower($chosen) === strtolower($q->correct_option);
                        @endphp
                        <div class="p-3 rounded-3 border {{ $isCorrect ? 'border-success bg-success bg-opacity-10' : 'border-danger bg-danger bg-opacity-10' }}">
                            <div class="fw-bold small mb-1">{{ $idx + 1 }}. {{ $q->question }}</div>
                            <div class="small">
                                {{ __('messages.assignment_show_your_choice') }}: <strong class="text-uppercase">{{ $chosen }}</strong>
                                @if (!$isCorrect)
                                    &bull; {{ __('messages.assignment_show_answer_key') }}: <strong class="text-uppercase text-success">{{ $q->correct_option }}</strong>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($submission->teacher_feedback)
                <div class="mt-3 p-3 bg-warning bg-opacity-10 rounded-3 small">
                    <strong>{{ __('messages.assignment_show_teacher_feedback') }}:</strong> {{ $submission->teacher_feedback }}
                </div>
            @endif
        </div>

    <!-- JIKA BELUM DIKUMPULKAN: TAMPILKAN FORM KUIS ATAU FORM TUGAS -->
    @else
        <form action="{{ route('student.assignments.submit', $assignment->id) }}" method="POST">
            @csrf

            @if ($assignment->type === 'quiz')
                <div class="d-flex flex-column gap-3 mb-4">
                    @forelse ($assignment->questions as $index => $q)
                        <div class="card border-0 shadow-sm rounded-4 p-3 p-sm-4">
                            <h6 class="fw-bold mb-3">{{ $index + 1 }}. {{ $q->question }}</h6>
                            <div class="d-flex flex-column gap-2">
                                @foreach (['a', 'b', 'c', 'd'] as $opt)
                                    @php $field = 'option_' . $opt; @endphp
                                    <label class="p-2 p-sm-3 border rounded-3 d-flex align-items-start gap-2 gap-sm-3" style="cursor: pointer;">
                                        <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}" class="form-check-input mt-1 flex-shrink-0" required>
                                        <span class="small"><strong class="text-uppercase me-1">{{ $opt }}.</strong> {{ $q->$field }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="card border-0 shadow-sm rounded-4 p-4 text-center text-muted small">
                            {{ __('messages.assignment_show_empty_questions') }}
                        </div>
                    @endforelse
                </div>

                @if ($assignment->questions->count() > 0)
                    <div class="d-grid d-sm-block">
                        <button type="submit" class="btn btn-brand btn-lg fw-semibold px-4 rounded-3">
                            <i class="bi bi-send-check-fill me-1"></i> {{ __('messages.assignment_show_btn_submit_quiz') }}
                        </button>
                    </div>
                @endif
            @else
                <div class="card border-0 shadow-sm rounded-4 p-3 p-sm-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">{{ __('messages.assignment_show_written_answer_label') }}</label>
                        <textarea name="answer_text" rows="6" class="form-control rounded-3" placeholder="{{ __('messages.assignment_show_written_answer_placeholder') }}" required>{{ old('answer_text') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small">{{ __('messages.assignment_show_attachment_url_label') }}</label>
                        <input type="url" name="attachment_url" value="{{ old('attachment_url') }}" class="form-control rounded-3" placeholder="https://drive.google.com/...">
                        <small class="text-muted d-block mt-1">{!! __('messages.assignment_show_attachment_url_help') !!}</small>
                    </div>

                    <div class="d-grid d-sm-block">
                        <button type="submit" class="btn btn-brand fw-semibold px-4 py-2 rounded-3">
                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> {{ __('messages.assignment_show_btn_submit_task') }}
                        </button>
                    </div>
                </div>
            @endif
        </form>
    @endif
@endsection