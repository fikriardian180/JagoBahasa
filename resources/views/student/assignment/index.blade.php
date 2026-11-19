@extends('layouts.student')

@section('title', 'Tugas & Kuis — JagoBahasa')
@section('page_title', 'Tugas & Kuis')
@section('page_subtitle', 'Selesaikan evaluasi tata bahasa, kosakata, dan praktik berbicara Anda')

@section('content')
    <!-- FILTER TAB STATUS -->
    <ul class="nav nav-pills mb-4 gap-2" id="assignmentFilterTabs">
        <li class="nav-item">
            <button class="nav-link active fw-semibold px-3 rounded-3" data-filter="all">Semua Evaluasi</button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-semibold px-3 rounded-3 bg-white border text-dark" data-filter="pending">Belum Dikerjakan</button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-semibold px-3 rounded-3 bg-white border text-dark" data-filter="done">Sudah Selesai</button>
        </li>
    </ul>

    <div class="row g-3" id="assignmentList">
        @forelse ($assignments as $item)
            @php
                $submission = $item->submissions->first();
                $isDone = $submission !== null;
                $isOverdue = !$isDone && now()->gt($item->due_date);
            @endphp

            <div class="col-md-6 assignment-item" data-status="{{ $isDone ? 'done' : 'pending' }}">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                        <div class="d-flex flex-wrap gap-1">
                            @if ($item->type === 'quiz')
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                                    <i class="bi bi-ui-checks me-1"></i>Kuis Interaktif
                                </span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">
                                    <i class="bi bi-file-earmark-text me-1"></i>Tugas Praktik
                                </span>
                            @endif
                            <span class="badge bg-brand-soft text-brand">{{ $item->course->language }}</span>
                        </div>

                        @if ($isDone)
                            <span class="badge bg-success rounded-pill px-3">
                                <i class="bi bi-check-circle me-1"></i>Selesai
                                @if ($submission->score !== null)
                                    ({{ number_format($submission->score, 0) }})
                                @endif
                            </span>
                        @elseif ($isOverdue)
                            <span class="badge bg-danger rounded-pill px-3">Terlewat</span>
                        @else
                            <span class="badge bg-light text-dark border rounded-pill px-3">Aktif</span>
                        @endif
                    </div>

                    <h6 class="fw-bold mb-1 mt-1">{{ $item->title }}</h6>
                    <small class="text-muted d-block mb-3">Kelas: {{ $item->course->title }}</small>

                    <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                        <small class="{{ $isOverdue ? 'text-danger fw-semibold' : 'text-muted' }}">
                            <i class="bi bi-clock me-1"></i>Tenggat: {{ $item->due_date->format('d M Y, H:i') }}
                        </small>

                        <a href="{{ route('student.assignments.show', $item->id) }}" class="btn {{ $isDone ? 'btn-outline-brand' : 'btn-brand' }} btn-sm fw-semibold px-3 rounded-3">
                            {{ $isDone ? 'Lihat Hasil' : ($item->type === 'quiz' ? 'Mulai Kuis' : 'Kerjakan') }}
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                    <div class="mx-auto bg-brand-soft text-brand rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-clipboard-check fs-2"></i>
                    </div>
                    <h6 class="fw-bold">Belum Ada Tugas atau Kuis</h6>
                    <p class="text-muted small mb-0">Pengajar belum memberikan tugas maupun kuis pada kelas yang Anda ikuti.</p>
                </div>
            </div>
        @endforelse
    </div>
@endsection

@push('scripts')
<script>
    const filterButtons = document.querySelectorAll('#assignmentFilterTabs button');
    const items = document.querySelectorAll('.assignment-item');

    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            filterButtons.forEach(b => {
                b.classList.remove('active', 'btn-brand');
                b.classList.add('bg-white', 'border', 'text-dark');
            });
            btn.classList.add('active');
            btn.classList.remove('bg-white', 'border', 'text-dark');

            const filter = btn.getAttribute('data-filter');
            items.forEach(item => {
                const status = item.getAttribute('data-status');
                item.style.display = (filter === 'all' || status === filter) ? '' : 'none';
            });
        });
    });
</script>
@endpush