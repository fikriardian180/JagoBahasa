@extends('layouts.admin')

@section('title', 'Data Murid & Guru — Administrator')
@section('page_title', 'Data Murid & Guru Terdaftar')
@section('page_subtitle', 'Kelola dan pantau seluruh akun pengajar serta peserta didik')

@section('content')
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4">
        <form method="GET" action="{{ route('admin.users') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <select name="role" class="form-select form-select-sm rounded-3" onchange="this.form.submit()">
                    <option value="all" {{ $roleFilter === 'all' ? 'selected' : '' }}>Semua Peran (Murid &amp; Guru)</option>
                    <option value="student" {{ $roleFilter === 'student' ? 'selected' : '' }}>Hanya Murid (Pelajar)</option>
                    <option value="teacher" {{ $roleFilter === 'teacher' ? 'selected' : '' }}>Hanya Guru (Pengajar)</option>
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm rounded-3" placeholder="Cari nama atau email pengguna...">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-brand btn-sm w-100 fw-semibold rounded-3">Cari</button>
            </div>
        </form>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 small">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Nama Lengkap</th>
                        <th>Email</th>
                        <th>Peran</th>
                        <th>Aktivitas Kelas</th>
                        <th>Tanggal Daftar</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td class="ps-4 fw-bold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if ($user->role === 'teacher')
                                    <span class="badge bg-warning bg-opacity-25 text-dark border border-warning border-opacity-50">Guru (Pengajar)</span>
                                @else
                                    <span class="badge bg-brand-soft text-brand border border-success border-opacity-25">Murid (Pelajar)</span>
                                @endif
                            </td>
                            <td>
                                @if ($user->role === 'teacher')
                                    Mengampu <strong>{{ $user->taught_courses_count }}</strong> kelas
                                @else
                                    Mengikuti <strong>{{ $user->enrolled_courses_count }}</strong> kelas
                                @endif
                            </td>
                            <td class="text-muted">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="text-end pe-4">
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus akun {{ $user->name }} secara permanen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-3">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-5 text-muted">Tidak ada data pengguna ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection