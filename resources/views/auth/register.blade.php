<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.btn_register') }} — JagoBahasa</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

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

    <!-- Form Registrasi -->
    <div class="container my-auto py-4" style="max-width: 460px;">
        <div class="text-center mb-4">
            <a href="{{ url('/') }}" class="d-inline-flex align-items-center gap-2 text-brand text-decoration-none fw-bold fs-4 font-display mb-2">
                <span class="brand-icon d-inline-flex align-items-center justify-content-center rounded-3 text-white">
                    <i class="bi bi-translate"></i>
                </span>
                <span>JagoBahasa</span>
            </a>
            <p class="text-muted small mb-0">{{ __('messages.auth_register_subtitle') }}</p>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4">
            @if ($errors->any())
                <div class="alert alert-danger border-0 rounded-3 small py-2 mb-3">
                    <i class="bi bi-exclamation-circle-fill me-1"></i>{{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label small fw-semibold">{{ __('messages.auth_fullname_label') }}</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control rounded-3 @error('name') is-invalid @enderror" placeholder="{{ __('messages.auth_fullname_placeholder') }}" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label small fw-semibold">{{ __('messages.auth_role_label') }}</label>
                    <select name="role" id="role" class="form-select rounded-3 @error('role') is-invalid @enderror" required>
                        <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>{{ __('messages.auth_role_student') }}</option>
                        <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>{{ __('messages.auth_role_teacher') }}</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label small fw-semibold">{{ __('messages.auth_email_label') }}</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control rounded-3 @error('email') is-invalid @enderror" placeholder="nama@email.com" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label small fw-semibold">{{ __('messages.auth_password_label') }}</label>
                    <input type="password" name="password" id="password" class="form-control rounded-3 @error('password') is-invalid @enderror" placeholder="{{ __('messages.auth_password_placeholder') }}" required>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label small fw-semibold">{{ __('messages.auth_password_confirm_label') }}</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control rounded-3" placeholder="{{ __('messages.auth_password_confirm_placeholder') }}" required>
                </div>

                <button type="submit" class="btn btn-brand w-100 fw-semibold py-2 rounded-3">
                    {{ __('messages.auth_btn_register') }} <i class="bi bi-arrow-right ms-1"></i>
                </button>
            </form>

            <hr class="my-4 opacity-25">

            <p class="text-center small text-muted mb-0">
                {{ __('messages.auth_have_account') }}
                <a href="{{ route('login') }}" class="text-brand fw-semibold text-decoration-none">{{ __('messages.auth_login_here') }}</a>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>