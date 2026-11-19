@extends('layouts.teacher')

@section('title', 'Penilaian Kiriman — ' . $assignment->title)
@section('page_title', 'Hasil & Penilaian: ' . $assignment->title)
@section('page_subtitle', 'Kelas: ' . $assignment->course->title)

@section('content')
    <div class="mb-3">
        <a href="{{ route('teacher.assignments.index') }}" class="text-decoration-none text-muted small fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Tugas &amp; Penilaian
        </a>
    </div>

    <div class="d-flex flex-column gap-3">
        @forelse ($assignment->submissions as $sub)
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="fw-bold">{{ $sub->student->name }}</span>
                            <span class="text-muted small">({{ $sub->student->email }})</span>
                            @if ($sub->score !== null)
                                <span class="badge bg-success rounded-pill ms-auto">Nilai: {{ number_format($sub->score, 0) }}/100</span>
                            @else
                                <span class="badge bg-warning text-dark rounded-pill ms-auto">Belum Dinilai</span>
                            @endif
                        </div>

                        <small class="text-muted d-block mb-2">
                            Dikirim pada: {{ \Carbon\Carbon::parse($sub->submitted_at)->format('d M Y, H:i') }} WIB
                        </small>

                        <div class="p-3 bg-light border rounded-3 small">
                            @if ($assignment->type === 'quiz')
                                <span class="text-muted">Kuis pilihan ganda dikoreksi otomatis oleh sistem.</span>
                            @else
                                <div class="mb-1"><strong>Jawaban Murid:</strong></div>
                                <p class="mb-2">{{ $sub->answer_text ?: 'Tidak ada catatan teks.' }}</p>
                                @if ($sub->file_url)
                                    <a href="{{ $sub->file_url }}" target="_blank" class="btn btn-sm btn-outline-brand rounded-3">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka Tautan / Rekaman Tugas
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-5 border-start-lg">
                        <form action="{{ route('teacher.submissions.grade', $sub->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-2">
                                <label class="form-label small fw-semibold">Nilai (0 – 100)</label>
                                <input type="number" name="score" step="0.5" min="0" max="100" value="{{ old('score', $sub->score) }}" class="form-control form-control-sm rounded-3" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Catatan Umpan Balik Pengajar</label>
                                <textarea name="feedback" rows="2" class="form-control form-control-sm rounded-3" placeholder="Berikan koreksi tata bahasa atau apresiasi...">{{ old('feedback', $sub->feedback) }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-brand btn-sm fw-semibold w-100 rounded-3">
                                <i class="bi bi-check2-circle me-1"></i> Simpan Nilai
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center text-muted small">
                Belum ada murid yang mengumpulkan jawaban untuk evaluasi ini.
            </div>
        @endforelse
    </div>
@endsection