<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Portal — JagoBahasa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light d-flex align-items-center min-vh-100">
    <div class="container" style="max-width: 440px;">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
            <h5 class="fw-bold mb-1">Pilih Portal Tujuan</h5>
            <p class="text-muted small mb-4">Email ini terdaftar pada lebih dari satu peran. Silakan pilih portal yang ingin dibuka:</p>

            <div class="d-flex flex-column gap-2">
                @foreach ($accounts as $account)
                    <form action="{{ route('login.select-role') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $account->id }}">
                        <button type="submit" class="btn btn-outline-brand w-100 py-3 rounded-3 fw-semibold d-flex align-items-center justify-content-between px-3">
                            <span>
                                <i class="bi {{ $account->role === 'teacher' ? 'bi-easel2-fill' : 'bi-mortarboard-fill' }} me-2"></i>
                                Masuk sebagai {{ $account->role === 'teacher' ? 'Guru (Pengajar)' : 'Murid (Pelajar)' }}
                            </span>
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </form>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>