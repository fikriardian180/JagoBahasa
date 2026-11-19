@extends('layouts.admin')

@section('title', 'Rekap Nilai Keseluruhan Murid — Administrator')
@section('page_title', 'Rekap Nilai Keseluruhan Murid')
@section('page_subtitle', 'Pantau capaian rata-rata nilai dan rincian hasil evaluasi setiap murid')

@section('content')
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4">
        <form method="GET" action="{{ route('admin.grades') }}" class="row g-2 align-items-center">
            <div class="col-md-10">
                <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm rounded-3" placeholder="Cari nama atau email murid...">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-brand btn-sm w-100 fw-semibold rounded-3">Cari Murid</button>
            </div>
        </form>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 small">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Nama Murid</th>
                        <th>Kelas Diikuti</th>
                        <th class="text-center">Tugas/Kuis Selesai</th>
                        <th class="text-center">Rata-Rata Nilai</th>
                        <th class="text-end pe-4">Rincian Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold">{{ $student->name }}</div>
                                <small class="text-muted">{{ $student->email }}</small>
                            </td>
                            <td>
                                @forelse ($student->enrolledCourses as $course)
                                    <span class="badge bg-light text-dark border mb-1">{{ $course->title }}</span>
                                @empty
                                    <span class="text-muted">-</span>
                                @endforelse
                            </td>
                            <td class="text-center">
                                <span class="fw-semibold">{{ $student->graded_count }}</span> / {{ $student->total_submissions }} Dinilai
                            </td>
                            <td class="text-center">
                                @if ($student->average_score !== null)
                                    <span class="badge {{ $student->average_score >= 75 ? 'bg-success' : 'bg-warning text-dark' }} fs-6 px-3 py-1 rounded-pill">
                                        {{ $student->average_score }}
                                    </span>
                                @else
                                    <span class="text-muted">Belum ada nilai</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-brand rounded-3 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#gradeDetail{{ $student->id }}">
                                    Detail ({{ $student->submissions->count() }})
                                </button>
                            </td>
                        </tr>
                        <tr class="collapse bg-light" id="gradeDetail{{ $student->id }}">
                            <td colspan="5" class="p-3 ps-4">
                                @if ($student->submissions->isEmpty())
                                    <small class="text-muted">Murid ini belum mengumpulkan tugas atau kuis apa pun.</small>
                                @else
                                    <div class="row g-2">
                                        @foreach ($student->submissions as $sub)
                                            <div class="col-md-6 col-lg-4">
                                                <div class="bg-white border rounded-3 p-2 d-flex justify-content-between align-items-center">
                                                    <div class="overflow-hidden pe-2">
                                                        <div class="fw-bold text-truncate">{{ $sub->assignment->title ?? 'Evaluasi' }}</div>
                                                        <small class="text-muted d-block text-truncate">{{ $sub->assignment->course->title ?? '-' }}</small>
                                                    </div>
                                                    <span class="badge {{ $sub->score !== null ? 'bg-brand-soft text-brand' : 'bg-secondary bg-opacity-10 text-secondary' }}">
                                                        {{ $sub->score !== null ? number_format($sub->score, 0) . '/100' : 'Menunggu' }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada murid terdaftar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection