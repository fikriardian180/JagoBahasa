@extends('layouts.admin')

@section('title', 'Dashboard Administrator — JagoBahasa')
@section('page_title', 'Ringkasan Sistem')
@section('page_subtitle', 'Statistik menyeluruh pengguna, kelas bahasa, dan evaluasi pembelajaran')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-brand-soft text-brand rounded-3 p-3"><i class="bi bi-mortarboard-fill fs-4"></i></div>
                    <div>
                        <small class="text-muted d-block">Total Murid</small>
                        <h4 class="fw-extrabold mb-0">{{ $totalStudents }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3"><i class="bi bi-person-workspace fs-4"></i></div>
                    <div>
                        <small class="text-muted d-block">Total Pengajar</small>
                        <h4 class="fw-extrabold mb-0">{{ $totalTeachers }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3"><i class="bi bi-easel2-fill fs-4"></i></div>
                    <div>
                        <small class="text-muted d-block">Kelas Aktif</small>
                        <h4 class="fw-extrabold mb-0">{{ $totalCourses }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-info bg-opacity-10 text-info rounded-3 p-3"><i class="bi bi-graph-up-arrow fs-4"></i></div>
                    <div>
                        <small class="text-muted d-block">Rata-Rata Nilai</small>
                        <h4 class="fw-extrabold mb-0">{{ $averageScore ? number_format($averageScore, 1) : '-' }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h6 class="fw-bold mb-3">Daftar Seluruh Kelas Bahasa</h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle small mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Kelas</th>
                                <th>Pengajar</th>
                                <th>Kode</th>
                                <th class="text-center">Murid</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($courses as $course)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $course->title }}</div>
                                        <small class="text-muted">{{ $course->language }}</small>
                                    </td>
                                    <td>{{ $course->teacher->name ?? '-' }}</td>
                                    <td><span class="badge bg-light text-dark border font-monospace">{{ $course->class_code }}</span></td>
                                    <td class="text-center fw-bold">{{ $course->students_count }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada kelas terdaftar.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Pengguna Terbaru</h6>
                    <a href="{{ route('admin.users') }}" class="small text-brand text-decoration-none fw-semibold">Lihat Semua</a>
                </div>
                @forelse ($recentUsers as $u)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom small">
                        <div>
                            <div class="fw-bold">{{ $u->name }}</div>
                            <div class="text-muted" style="font-size: 0.78rem;">{{ $u->email }}</div>
                        </div>
                        @if ($u->role === 'teacher')
                            <span class="badge bg-warning bg-opacity-25 text-dark">Guru</span>
                        @else
                            <span class="badge bg-brand-soft text-brand">Murid</span>
                        @endif
                    </div>
                @empty
                    <p class="text-muted small mb-0">Belum ada pengguna terdaftar.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection