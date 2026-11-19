@extends('layouts.teacher')

@section('title', 'Buat Tugas atau Kuis — Portal Pengajar')
@section('page_title', 'Buat Evaluasi Baru')
@section('page_subtitle', 'Pilih antara Tugas Praktik (Esai/Tautan) atau Kuis Pilihan Ganda dengan koreksi otomatis')

@section('content')
    <div class="mb-3">
        <a href="{{ route('teacher.assignments.index') }}" class="text-decoration-none text-muted small fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Tugas &amp; Penilaian
        </a>
    </div>

    <form action="{{ route('teacher.assignments.store') }}" method="POST">
        @csrf
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h6 class="fw-bold mb-3">1. Informasi Dasar Evaluasi</h6>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Pilih Kelas</label>
                    <select name="course_id" class="form-select rounded-3" required>
                        <option value="">-- Pilih Kelas Diampu --</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->title }} ({{ $course->language }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Jenis Evaluasi</label>
                    <select name="type" id="evaluationType" class="form-select rounded-3" required>
                        <option value="assignment">Tugas Praktik (Dinilai Manual oleh Pengajar)</option>
                        <option value="quiz">Kuis Pilihan Ganda (Skor Otomatis)</option>
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <label class="form-label small fw-semibold">Judul Tugas / Kuis</label>
                    <input type="text" name="title" class="form-control rounded-3" placeholder="Contoh: Kuis Kosakata Bahasa Arab Bab 1" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Batas Waktu (Tenggat)</label>
                    <input type="datetime-local" name="due_date" class="form-control rounded-3" required>
                </div>
            </div>

            <div class="mb-0">
                <label class="form-label small fw-semibold">Instruksi / Petunjuk Pengerjaan</label>
                <textarea name="description" rows="3" class="form-control rounded-3" placeholder="Tuliskan instruksi soal atau ketentuan pengumpulan..."></textarea>
            </div>
        </div>

        <!-- PEMBUAT SOAL KUIS PILIHAN GANDA -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 d-none" id="quizBuilderSection">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold mb-0">2. Butir Soal Pilihan Ganda</h6>
                    <small class="text-muted">Sistem akan otomatis menghitung skor murid berdasarkan kunci jawaban berikut</small>
                </div>
                <button type="button" class="btn btn-outline-brand btn-sm fw-semibold rounded-3" id="addQuestionBtn">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Soal
                </button>
            </div>

            <div id="questionsContainer" class="d-flex flex-column gap-3"></div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('teacher.assignments.index') }}" class="btn btn-light rounded-3 px-4">Batal</a>
            <button type="submit" class="btn btn-brand fw-semibold px-4 rounded-3">
                <i class="bi bi-send-check me-1"></i> Terbitkan Evaluasi
            </button>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    const typeSelect = document.getElementById('evaluationType');
    const quizSection = document.getElementById('quizBuilderSection');
    const container = document.getElementById('questionsContainer');
    const addBtn = document.getElementById('addQuestionBtn');
    let questionIndex = 0;

    function createQuestionCard(idx) {
        return `
            <div class="p-3 border rounded-3 bg-light question-block">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong class="small text-brand">Soal #${idx + 1}</strong>
                    <button type="button" class="btn btn-sm btn-link text-danger text-decoration-none p-0 remove-q-btn">Hapus</button>
                </div>
                <div class="mb-2">
                    <input type="text" name="questions[${idx}][question_text]" class="form-control form-control-sm rounded-3 quiz-input" placeholder="Tulis pertanyaan di sini...">
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-md-6"><input type="text" name="questions[${idx}][option_a]" class="form-control form-control-sm rounded-3 quiz-input" placeholder="Pilihan A"></div>
                    <div class="col-md-6"><input type="text" name="questions[${idx}][option_b]" class="form-control form-control-sm rounded-3 quiz-input" placeholder="Pilihan B"></div>
                    <div class="col-md-6"><input type="text" name="questions[${idx}][option_c]" class="form-control form-control-sm rounded-3 quiz-input" placeholder="Pilihan C"></div>
                    <div class="col-md-6"><input type="text" name="questions[${idx}][option_d]" class="form-control form-control-sm rounded-3 quiz-input" placeholder="Pilihan D"></div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <label class="small fw-semibold mb-0">Kunci Jawaban:</label>
                    <select name="questions[${idx}][correct_option]" class="form-select form-select-sm w-auto rounded-3 quiz-input">
                        <option value="A">Pilihan A</option>
                        <option value="B">Pilihan B</option>
                        <option value="C">Pilihan C</option>
                        <option value="D">Pilihan D</option>
                    </select>
                </div>
            </div>
        `;
    }

    function addQuestion() {
        container.insertAdjacentHTML('beforeend', createQuestionCard(questionIndex++));
        toggleRequired(typeSelect.value === 'quiz');
    }

    function toggleRequired(isQuiz) {
        document.querySelectorAll('.quiz-input').forEach(el => {
            el.required = isQuiz;
        });
    }

    typeSelect.addEventListener('change', () => {
        const isQuiz = typeSelect.value === 'quiz';
        quizSection.classList.toggle('d-none', !isQuiz);
        if (isQuiz && container.children.length === 0) {
            addQuestion();
        }
        toggleRequired(isQuiz);
    });

    addBtn.addEventListener('click', addQuestion);

    container.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-q-btn') && container.children.length > 1) {
            e.target.closest('.question-block').remove();
        }
    });
</script>
@endpush