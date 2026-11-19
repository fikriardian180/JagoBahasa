@extends('layouts.teacher')

@section('title', 'Buat Kelas Baru — JagoBahasa')
@section('page_title', 'Buat Kelas Bahasa Baru')
@section('page_subtitle', 'Kode kelas unik akan dibuat secara otomatis setelah kelas disimpan')

@section('content')
    <div class="mb-3">
        <a href="{{ route('teacher.courses.index') }}" class="text-decoration-none text-muted small fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Kelas
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                <form action="{{ route('teacher.courses.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Kelas / Mata Pelajaran</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="form-control rounded-3 @error('title') is-invalid @enderror" placeholder="Contoh: Academic English & Conversation" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Bahasa yang Diajarkan</label>
                            <select name="language" class="form-select rounded-3" required>
                                <option value="Bahasa Inggris" {{ old('language') === 'Bahasa Inggris' ? 'selected' : '' }}>Bahasa Inggris (English)</option>
                                <option value="Bahasa Arab" {{ old('language') === 'Bahasa Arab' ? 'selected' : '' }}>Bahasa Arab (العربية)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Tingkat Kemahiran (Level)</label>
                            <select name="level" class="form-select rounded-3" required>
                                <option value="Pemula (Beginner)">Pemula (Beginner)</option>
                                <option value="Menengah (Intermediate)">Menengah (Intermediate)</option>
                                <option value="Mahir (Advanced)">Mahir (Advanced)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small">Deskripsi &amp; Tujuan Pembelajaran</label>
                        <textarea name="description" rows="4" class="form-control rounded-3" placeholder="Jelaskan kompetensi tata bahasa, kosakata, atau percakapan yang akan dipelajari murid...">{{ old('description') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('teacher.courses.index') }}" class="btn btn-light rounded-3 px-4">Batal</a>
                        <button type="submit" class="btn btn-brand fw-semibold px-4 rounded-3">
                            <i class="bi bi-check-circle me-1"></i> Simpan &amp; Buat Kode Kelas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection