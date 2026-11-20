@extends('layouts.teacher')

@section('title', $course->title . ' — Portal Pengajar')
@section('page_title', $course->title)
@section('page_subtitle', $course->language . ' • ' . ($course->level ?? 'Umum'))

@section('content')
    <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <a href="{{ route('teacher.courses.index') }}" class="text-decoration-none text-muted small fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Kelas Diampu
        </a>

        <div class="d-flex align-items-center gap-2 bg-white border rounded-3 px-3 py-2 shadow-sm">
            <span class="small text-muted">Kode Akses Murid:</span>
            <strong class="font-monospace text-brand fs-6" id="courseCodeText">{{ $course->class_code }}</strong>
            <button type="button" class="btn btn-sm btn-light border ms-1" onclick="navigator.clipboard.writeText('{{ $course->class_code }}'); this.innerHTML='<i class=\'bi bi-check2 text-success\'></i>';">
                <i class="bi bi-clipboard"></i>
            </button>
        </div>
    </div>

    <div class="row g-4">
        <!-- KIRI: FORM TAMBAH BAB MATERI & DAFTAR MATERI -->
        <div class="col-lg-8">
            <!-- Form Tambah Materi -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-plus-square-fill text-brand me-2"></i>Tambah Bab Materi Pembelajaran</h6>
                <form action="{{ route('teacher.courses.lessons.store', $course->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3 mb-3">
                        <div class="col-md-9">
                            <label class="form-label small fw-semibold">Judul Bab / Topik Materi</label>
                            <input type="text" name="title" class="form-control rounded-3" placeholder="Contoh: Bab 1 — Perkenalan Diri & Pelafalan Dasar" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Urutan Bab</label>
                            <input type="number" name="order_number" value="{{ $course->lessons->count() + 1 }}" min="1" class="form-control rounded-3">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Isi Materi / Penjelasan Tata Bahasa &amp; Kosakata</label>
                        <textarea name="content" rows="4" class="form-control rounded-3" placeholder="Tuliskan penjelasan materi, contoh kalimat, atau kosakata penting..." required></textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Unggah File Materi (PDF, Word, PPT, Audio MP3)</label>
                            <input type="file" name="media_file" class="form-control rounded-3 @error('media_file') is-invalid @enderror" accept=".pdf,.doc,.docx,.ppt,.pptx,.mp3,.wav,.m4a,.jpg,.jpeg,.png">
                            <small class="text-muted">Maksimal 10 MB.</small>
                            @error('media_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Atau Tautan Eksternal (Opsional)</label>
                            <input type="url" name="media_url" class="form-control rounded-3 @error('media_url') is-invalid @enderror" placeholder="https://...">
                            <small class="text-muted">Gunakan jika materi berupa tautan YouTube / Google Drive.</small>
                            @error('media_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-brand btn-sm fw-semibold px-4 py-2 rounded-3">
                        <i class="bi bi-journal-plus me-1"></i> Tambahkan Materi
                    </button>
                </form>
            </div>

            <!-- Daftar Bab Materi Tersimpan -->
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h6 class="fw-bold mb-3">Kurikulum &amp; Bab Materi ({{ $course->lessons->count() }})</h6>

                @forelse ($course->lessons as $lesson)
                    <div class="p-3 border rounded-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div>
                                <span class="badge bg-brand-soft text-brand mb-1">Bab {{ $lesson->order_number }}</span>
                                <h6 class="fw-bold mb-1">{{ $lesson->title }}</h6>
                                <p class="text-muted small mb-1">{{ Str::limit($lesson->content, 160) }}</p>
                                @if ($lesson->media_url)
                                    <a href="{{ $lesson->media_url }}" target="_blank" class="small text-brand fw-semibold text-decoration-none">
                                        <i class="bi {{ str_starts_with($lesson->media_url, '/storage/') ? 'bi-file-earmark-arrow-down-fill' : 'bi-link-45deg' }}"></i>
                                        {{ str_starts_with($lesson->media_url, '/storage/') ? 'Lihat / Unduh File Materi' : 'Buka Tautan Media' }}
                                    </a>
                                @endif
                            </div>
                            <form action="{{ route('teacher.courses.lessons.destroy', [$course->id, $lesson->id]) }}" method="POST" onsubmit="return confirm('Hapus bab materi ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-3">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted small">
                        Belum ada materi di kelas ini. Tambahkan bab pertama menggunakan formulir di atas.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- KANAN: DAFTAR MURID TERDAFTAR & HAPUS KELAS -->
        <div class="col-lg-4 d-flex flex-column gap-4">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-people-fill text-brand me-2"></i>Peserta Terdaftar ({{ $course->students->count() }})</h6>

                @forelse ($course->students as $student)
                    <div class="d-flex align-items-center gap-2 py-2 border-bottom">
                        <div class="avatar-circle bg-brand-soft text-brand fw-bold flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.85rem;">
                            {{ strtoupper(substr($student->name, 0, 1)) }}
                        </div>
                        <div class="overflow-hidden">
                            <div class="fw-semibold small text-truncate">{{ $student->name }}</div>
                            <div class="text-muted text-truncate" style="font-size: 0.75rem;">{{ $student->email }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted small mb-0">Belum ada murid yang bergabung dengan kode <strong>{{ $course->class_code }}</strong>.</p>
                @endforelse
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4 border-start border-4 border-danger">
                <h6 class="fw-bold text-danger mb-1">Zona Berbahaya</h6>
                <p class="text-muted small mb-3">Menghapus kelas akan menghapus seluruh bab materi, tugas, dan data nilai murid di kelas ini.</p>
                <form action="{{ route('teacher.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kelas ini secara permanen?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100 rounded-3">
                        <i class="bi bi-trash3 me-1"></i> Hapus Kelas Ini
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection