@extends('layouts.student')

@section('title', 'Kelas Online — JagoBahasa')
@section('page_title', 'Kelas Online (Live Class)')
@section('page_subtitle', 'Jadwal tatap muka virtual bersama pengajar bahasa')

@section('content')
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h5 class="fw-bold mb-1">Jadwal Kelas Online</h5>
                <p class="text-muted small mb-0">
                    Ikuti sesi interaktif langsung dengan pengajar untuk melatih pelafalan (<em>speaking</em>), tata bahasa (<em>grammar</em>), dan tanya jawab materi.
                </p>
            </div>
            <span class="badge bg-brand-soft text-brand px-3 py-2 rounded-3 align-self-start align-self-md-center">
                <i class="bi bi-broadcast me-1"></i> {{ $upcomingClasses->count() }} Sesi Terjadwal
            </span>
        </div>
    </div>

    <!-- SESI MENDATANG / SEDANG BERLANGSUNG -->
    <div class="row g-4 mb-5">
        @forelse ($upcomingClasses as $session)
            @php
                // Dianggap sedang berlangsung jika waktu mulai sudah lewat <= 2 jam atau tinggal <= 15 menit lagi
                $isLiveNow = now()->between($session->scheduled_at->copy()->subMinutes(15), $session->scheduled_at->copy()->addMinutes($session->duration_minutes));
            @endphp

            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 d-flex flex-column {{ $isLiveNow ? 'border-start border-4 border-success' : '' }}">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                        <span class="badge bg-brand-soft text-brand">{{ $session->course->language }}</span>
                        @if ($isLiveNow)
                            <span class="badge bg-danger rounded-pill px-3">
                                <i class="bi bi-record-circle me-1"></i>Berlangsung Sekarang
                            </span>
                        @else
                            <span class="badge bg-light text-dark border rounded-pill px-3">
                                {{ $session->scheduled_at->diffForHumans() }}
                            </span>
                        @endif
                    </div>

                    <h5 class="fw-bold mb-1">{{ $session->title }}</h5>
                    <small class="text-muted d-block mb-3">
                        Kelas: <strong>{{ $session->course->title }}</strong> &bull; Pengajar: {{ $session->course->teacher->name ?? 'Pengajar' }}
                    </small>

                    @if ($session->description)
                        <p class="text-muted small mb-3">{{ $session->description }}</p>
                    @endif

                    <div class="p-3 rounded-3 bg-light border small mb-4 mt-auto">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted"><i class="bi bi-calendar-event me-1"></i>Tanggal &amp; Waktu:</span>
                            <span class="fw-semibold">{{ $session->scheduled_at->format('d M Y, H:i') }} WIB</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted"><i class="bi bi-camera-video me-1"></i>Platform &amp; Durasi:</span>
                            <span class="fw-semibold">{{ $session->platform }} ({{ $session->duration_minutes }} mnt)</span>
                        </div>
                    </div>

                    <a href="{{ $session->meeting_url }}" target="_blank" rel="noopener noreferrer" class="btn {{ $isLiveNow ? 'btn-brand' : 'btn-outline-brand' }} fw-semibold w-100 rounded-3">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Gabung Ruang Pertemuan
                    </a>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                    <div class="mx-auto bg-brand-soft text-brand rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                        <i class="bi bi-camera-video fs-2"></i>
                    </div>
                    <h5 class="fw-bold">Belum Ada Sesi Kelas Online Aktif</h5>
                    <p class="text-muted small mx-auto mb-0" style="max-width: 400px;">
                        Pengajar belum menjadwalkan kelas online baru. Periksa kembali halaman ini atau notifikasi Anda secara berkala.
                    </p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- RIWAYAT SESI YANG SUDAH SELESAI -->
    @if ($pastClasses->isNotEmpty())
        <h6 class="fw-bold text-muted mb-3">Riwayat Pertemuan Sebelumnya</h6>
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Topik Pertemuan</th>
                            <th>Kursus</th>
                            <th>Waktu Pelaksanaan</th>
                            <th class="text-end pe-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pastClasses as $past)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $past->title }}</td>
                                <td>{{ $past->course->title }}</td>
                                <td class="text-muted">{{ $past->scheduled_at->format('d M Y, H:i') }} WIB</td>
                                <td class="text-end pe-4"><span class="badge bg-secondary bg-opacity-10 text-secondary">Selesai</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection