<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.btn_login') }} — JagoBahasa LMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'><rect width='64' height='64' rx='16' fill='%23047857'/><text x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' fill='white' font-family='sans-serif' font-weight='800' font-size='28'>JB</text></svg>">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <!-- Bar Atas: Kembali ke Beranda & Pengganti Bahasa -->
    <div class="container-fluid px-4 pt-3 d-flex justify-content-between align-items-center">
        <a href="{{ url('/') }}" class="text-decoration-none text-muted small fw-semibold">
            <i class="bi bi-arrow-left me-1"></i>{{ __('messages.nav_home') }}
        </a>

        <div class="dropdown">
            <button class="btn btn-white bg-white btn-sm border dropdown-toggle fw-medium rounded-pill px-3 shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-globe2 me-1"></i>{{ strtoupper(app()->getLocale()) }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-1">
                <li>
                    <a class="dropdown-item {{ app()->getLocale() === 'id' ? 'active' : '' }}" href="{{ route('lang.switch', 'id') }}">
                        Bahasa Indonesia (ID)
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}" href="{{ route('lang.switch', 'en') }}">
                        English (EN)
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Form Login -->
    <div class="container my-auto py-4">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="text-center mb-4">
                    <a href="{{ url('/') }}" class="d-inline-flex align-items-center gap-2 text-decoration-none text-brand">
                        <span class="brand-icon d-inline-flex align-items-center justify-content-center rounded-3 text-white">
                            <i class="bi bi-translate"></i>
                        </span>
                        <span class="font-display fw-extrabold fs-3">JagoBahasa</span>
                    </a>
                    <p class="text-muted small mt-2 mb-0">{{ __('messages.auth_login_subtitle') }}</p>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger small py-2 rounded-3">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">{{ __('messages.auth_email_label') }}</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control rounded-3" placeholder="nama@email.com" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">{{ __('messages.auth_password_label') }}</label>
                            <input type="password" name="password" class="form-control rounded-3" placeholder="••••••••" required>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label small text-muted" for="remember">{{ __('messages.auth_remember_me') }}</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-brand w-100 fw-semibold py-2 rounded-3">
                            {{ __('messages.auth_btn_login') }} <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top small">
                        <span class="text-muted">{{ __('messages.auth_no_account') }}</span>
                        <a href="{{ route('register') }}" class="text-brand fw-bold text-decoration-none ms-1">{{ __('messages.auth_register_here') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>