@extends('layouts.student')

@section('title', 'Nilai & Progres — JagoBahasa')
@section('page_title', 'Nilai & Progres Belajar')
@section('page_subtitle', 'Rekapitulasi nilai tugas, kuis, dan pencapaian modul Anda')

@section('content')
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <h5 class="fw-bold mb-2">Rekapitulasi Nilai &amp; Evaluasi</h5>
        <p class="text-muted small mb-0">
            Lihat riwayat nilai dari seluruh tugas dan kuis yang telah Anda kumpulkan.
        </p>
    </div>

    @php
        $user = Auth::user();
        $submissions = \App\Models\AssignmentSubmission::with('assignment.course')
            ->where('user_id', $user->id)
            ->latest('submitted_at')
            ->get();
    @endphp

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small">
                    <tr>
                        <th class="py-3 px-4">Tugas / Kuis</th>
                        <th class="py-3">Kursus</th>
                        <th class="py-3">Tanggal Kirim</th>
                        <th class="py-3 text-center">Nilai</th>
                        <th class="py-3 px-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="small">
                    @forelse ($submissions as $sub)
                        <tr>
                            <td class="px-4 fw-bold">{{ $sub->assignment->title ?? 'Tugas' }}</td>
                            <td>{{ $sub->assignment->course->title ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($sub->submitted_at)->format('d M Y, H:i') }}</td>
                            <td class="text-center">
                                @if ($sub->score !== null)
                                    <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-2 fs-6">
                                        {{ number_format($sub->score, 0) }}
                                    </span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-dark px-3 py-2">Menunggu Dinilai</span>
                                @endif
                            </td>
                            <td class="px-4 text-end">
                                <a href="{{ route('student.assignments.show', $sub->assignment_id) }}" class="btn btn-sm btn-outline-brand rounded-3">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                Belum ada riwayat pengumpulan tugas atau kuis.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
