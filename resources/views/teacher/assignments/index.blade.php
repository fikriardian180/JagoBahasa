@extends('layouts.teacher')

@section('title', 'Tugas & Penilaian — Portal Pengajar')
@section('page_title', 'Tugas & Penilaian')
@section('page_subtitle', 'Buat tugas praktik, kuis pilihan ganda otomatis, dan nilai kiriman murid')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <span class="text-muted small">Total <strong>{{ $assignments->count() }}</strong> evaluasi dibuat</span>
        <a href="{{ route('teacher.assignments.create') }}" class="btn btn-brand fw-semibold rounded-3 px-3">
            <i class="bi bi-plus-circle me-1"></i> Buat Tugas / Kuis
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 small">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Judul Evaluasi</th>
                        <th>Kelas</th>
                        <th>Jenis</th>
                        <th>Tenggat</th>
                        <th>Pengumpulan</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($assignments as $item)
                        <tr>
                            <td class="ps-4 fw-bold">{{ $item->title }}</td>
                            <td>{{ $item->course->title }}</td>
                            <td>
                                @if ($item->type === 'quiz')
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">Kuis Otomatis</span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">Tugas Praktik</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ \Carbon\Carbon::parse($item->due_date)->format('d M Y, H:i') }}</td>
                            <td>
                                <span class="fw-semibold">{{ $item->submissions_count }} Kiriman</span>
                                @if ($item->ungraded_count > 0)
                                    <span class="badge bg-danger rounded-pill ms-1">{{ $item->ungraded_count }} Belum Dinilai</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('teacher.assignments.submissions', $item->id) }}" class="btn btn-sm btn-outline-brand fw-semibold rounded-3">
                                        Lihat &amp; Nilai
                                    </a>
                                    <form action="{{ route('teacher.assignments.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus tugas/kuis ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-3">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                Belum ada tugas atau kuis. Klik <strong>Buat Tugas / Kuis</strong> untuk memulai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection