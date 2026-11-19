@extends('layouts.student')

@section('title', 'Profil Saya — JagoBahasa')
@section('page_title', 'Pengaturan Profil')
@section('page_subtitle', 'Kelola informasi akun, email, dan keamanan kata sandi Anda')

@section('content')
    <div class="row g-4">
        <!-- KOLOM KIRI: RINGKASAN PROFIL -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
                <div class="mx-auto bg-brand text-white fw-extrabold rounded-circle d-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                <p class="text-muted small mb-3">{{ $user->email }}</p>

                <div class="d-inline-flex justify-content-center mb-3">
                    <span class="badge bg-brand-soft text-brand border border-success border-opacity-25 px-3 py-2 rounded-pill">
                        <i class="bi bi-person-badge me-1"></i> Peserta Kursus (Murid)
                    </span>
                </div>

                <div class="border-top pt-3 mt-2 text-start small text-muted">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Bergabung sejak:</span>
                        <span class="fw-semibold text-dark">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Kelas diikuti:</span>
                        <span class="fw-semibold text-dark">{{ $user->enrolledCourses()->count() }} Kelas</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: FORM EDIT EMAIL & PASSWORD -->
        <div class="col-lg-8 d-flex flex-column gap-4">
            <!-- 1. FORM UBAH NAMA & EMAIL -->
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                    <div class="bg-brand-soft text-brand rounded-3 p-2">
                        <i class="bi bi-person-lines-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">Informasi Dasar Akun</h6>
                        <small class="text-muted">Perbarui nama tampilan dan alamat email aktif Anda</small>
                    </div>
                </div>

                <form action="{{ route('student.profile.update-info') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label small fw-semibold">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-control rounded-3 @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label small fw-semibold">Alamat Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control rounded-3 @error('email') is-invalid @enderror" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-brand fw-semibold px-4 rounded-3">
                            <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan Profil
                        </button>
                    </div>
                </form>
            </div>

            <!-- 2. FORM UBAH KATA SANDI -->
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-2">
                        <i class="bi bi-shield-lock-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">Ubah Kata Sandi</h6>
                        <small class="text-muted">Gunakan minimal 8 karakter untuk menjaga keamanan akun</small>
                    </div>
                </div>

                <form action="{{ route('student.profile.update-password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label small fw-semibold">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password" id="current_password" class="form-control rounded-3 @error('current_password') is-invalid @enderror" placeholder="Masukkan kata sandi lama Anda" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="password" class="form-label small fw-semibold">Kata Sandi Baru</label>
                            <input type="password" name="password" id="password" class="form-control rounded-3 @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label small fw-semibold">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control rounded-3" placeholder="Ulangi kata sandi baru" required>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-outline-brand fw-semibold px-4 rounded-3">
                            <i class="bi bi-key-fill me-1"></i> Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection