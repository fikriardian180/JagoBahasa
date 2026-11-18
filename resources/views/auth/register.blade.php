<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun — JagoBahasa LMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light d-flex align-items-center min-vh-100 py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="text-center mb-4">
                    <a href="{{ url('/') }}" class="d-inline-flex align-items-center gap-2 text-decoration-none text-brand">
                        <span class="brand-icon d-inline-flex align-items-center justify-content-center rounded-3 text-white">
                            <i class="bi bi-translate"></i>
                        </span>
                        <span class="font-display fw-extrabold fs-3">JagoBahasa</span>
                    </a>
                    <p class="text-muted small mt-2 mb-0">Buat akun baru untuk mulai mengajar atau belajar</p>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger small py-2 rounded-3">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control rounded-3" placeholder="Masukkan nama lengkap" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control rounded-3" placeholder="nama@email.com" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Daftar Sebagai</label>
                            <select name="role" class="form-select rounded-3" required>
                                <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Murid (Peserta Kursus)</option>
                                <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Guru (Pengajar)</option>
                            </select>
                        </div>

                        <div class="row g-2 mb-4">
                            <div class="col-sm-6">
                                <label class="form-label small fw-semibold">Kata Sandi</label>
                                <input type="password" name="password" class="form-control rounded-3" placeholder="Min. 6 karakter" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small fw-semibold">Konfirmasi Sandi</label>
                                <input type="password" name="password_confirmation" class="form-control rounded-3" placeholder="Ulangi sandi" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-brand w-100 fw-semibold py-2 rounded-3">
                            Daftar Akun <i class="bi bi-check2-circle ms-1"></i>
                        </button>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top small">
                        <span class="text-muted">Sudah punya akun?</span>
                        <a href="{{ route('login') }}" class="text-brand fw-bold text-decoration-none ms-1">Masuk di sini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>