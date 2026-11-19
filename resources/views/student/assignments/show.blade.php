@extends('layouts.student')

@section('title', $assignment->title . ' — JagoBahasa')
@section('page_title', $assignment->title)
@section('page_subtitle', $assignment->course->title . ' • Tenggat: ' . $assignment->due_date->format('d M Y, H:i') . ' WIB')

@section('content')
    <div class="mb-3">
        <a href="{{ route('student.assignments.index') }}" class="text-decoration-none text-muted small fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Tugas &amp; Kuis
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
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge {{ $assignment->type === 'quiz' ? 'bg-info text-dark' : 'bg-warning text-dark' }}">
                {{ $assignment->type === 'quiz' ? 'Kuis Pilihan Ganda' : 'Tugas Praktik Bahasa' }}
            </span>
            <span class="badge bg-brand-soft text-brand">{{ $assignment->course->language }}</span>
        </div>
        <h5 class="fw-bold mb-2">Petunjuk Pengerjaan</h5>
        <p class="text-muted small mb-0" style="line-height: 1.7;">
            {!! nl2br(e($assignment->instructions ?? 'Bacalah setiap instruksi dengan saksama sebelum mengirimkan jawaban Anda.')) !!}
        </p>
    </div>

    <!-- JIKA SUDAH DIKUMPULKAN: TAMPILKAN HASIL & NILAI -->
    @if ($submission)
        <div class="card border-0 shadow-sm rounded-4 p-4 border-start border-4 border-success">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3 pb-3 border-bottom">
                <div>
                    <span class="badge bg-success mb-1"><i class="bi bi-check-circle-fill me-1"></i>Sudah Dikumpulkan</span>
                    <div class="small text-muted">Dikirim pada {{ \Carbon\Carbon::parse($submission->submitted_at)->format('d M Y, H:i') }} WIB</div>
                </div>
                <div class="bg-brand-soft px-4 py-2 rounded-3 text-center">
                    <small class="text-muted d-block">Nilai Anda</small>
                    <h3 class="fw-extrabold text-brand mb-0">
                        {{ $submission->score !== null ? number_format($submission->score, 0) . ' / 100' : 'Menunggu Penilaian' }}
                    </h3>
                </div>
            </div>

            @if ($assignment->type === 'task')
                <h6 class="fw-bold small">Jawaban Teks Anda:</h6>
                <div class="p-3 bg-light rounded-3 small mb-3">{!! nl2br(e($submission->answer_text)) !!}</div>

                @if ($submission->attachment_url)
                    <div class="small mb-3">
                        <strong>Tautan Lampiran (Audio/Dokumen):</strong>
                        <a href="{{ $submission->attachment_url }}" target="_blank" class="text-brand ms-1">{{ $submission->attachment_url }}</a>
                    </div>
                @endif
            @else
                @php $userAnswers = json_decode($submission->answer_text, true) ?? []; @endphp
                <h6 class="fw-bold small mb-3">Pembahasan Jawaban Kuis:</h6>
                <div class="d-flex flex-column gap-3">
                    @foreach ($assignment->questions as $idx => $q)
                        @php
                            $chosen = $userAnswers[$q->id] ?? '-';
                            $isCorrect = strtolower($chosen) === strtolower($q->correct_option);
                        @endphp
                        <div class="p-3 rounded-3 border {{ $isCorrect ? 'border-success bg-success bg-opacity-10' : 'border-danger bg-danger bg-opacity-10' }}">
                            <div class="fw-bold small mb-1">{{ $idx + 1 }}. {{ $q->question }}</div>
                            <div class="small">
                                Jawaban Anda: <strong class="text-uppercase">{{ $chosen }}</strong>
                                @if (!$isCorrect)
                                    &bull; Kunci Jawaban: <strong class="text-uppercase text-success">{{ $q->correct_option }}</strong>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($submission->teacher_feedback)
                <div class="mt-3 p-3 bg-warning bg-opacity-10 rounded-3 small">
                    <strong>Catatan Pengajar:</strong> {{ $submission->teacher_feedback }}
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
                        <div class="card border-0 shadow-sm rounded-4 p-4">
                            <h6 class="fw-bold mb-3">{{ $index + 1 }}. {{ $q->question }}</h6>
                            <div class="d-flex flex-column gap-2">
                                @foreach (['a', 'b', 'c', 'd'] as $opt)
                                    @php $field = 'option_' . $opt; @endphp
                                    <label class="p-3 border rounded-3 d-flex align-items-center gap-3" style="cursor: pointer;">
                                        <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}" class="form-check-input mt-0" required>
                                        <span class="small"><strong class="text-uppercase me-1">{{ $opt }}.</strong> {{ $q->$field }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="card border-0 shadow-sm rounded-4 p-4 text-center text-muted small">
                            Pengajar belum menambahkan butir soal pada kuis ini.
                        </div>
                    @endforelse
                </div>

                @if ($assignment->questions->count() > 0)
                    <button type="submit" class="btn btn-brand btn-lg fw-semibold px-4 rounded-3">
                        <i class="bi bi-send-check-fill me-1"></i> Selesai &amp; Kirim Jawaban Kuis
                    </button>
                @endif
            @else
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Jawaban Tertulis (Essay / Terjemahan / Transkrip)</label>
                        <textarea name="answer_text" rows="6" class="form-control rounded-3" placeholder="Tuliskan jawaban tugas bahasa Anda di sini..." required>{{ old('answer_text') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small">Tautan Rekaman Audio Speaking / File Google Drive (Opsional)</label>
                        <input type="url" name="attachment_url" value="{{ old('attachment_url') }}" class="form-control rounded-3" placeholder="https://drive.google.com/...">
                        <small class="text-muted">Gunakan kolom ini jika tugas meminta rekaman pelafalan (*speaking* / *muhadatsah*).</small>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-brand fw-semibold px-4 py-2 rounded-3">
                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> Kumpulkan Tugas
                        </button>
                    </div>
                </div>
            @endif
        </form>
    @endif
@endsection