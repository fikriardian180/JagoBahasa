@extends('layouts.teacher')

@section('title', 'Jadwal Kelas Online — Portal Pengajar')
@section('page_title', 'Jadwal Kelas Online (Live Class)')
@section('page_subtitle', 'Atur sesi tatap muka virtual dan bagikan tautan pertemuan kepada murid')

@section('content')
    <div class="row g-4">
        <!-- KOLOM KIRI: FORM JADWALKAN SESI BARU -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                    <div class="bg-brand-soft text-brand rounded-3 p-2">
                        <i class="bi bi-camera-video-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">Jadwalkan Sesi Baru</h6>
                        <small class="text-muted">Otomatis muncul di kalender &amp; lonceng murid</small>
                    </div>
                </div>

                <form action="{{ route('teacher.live-classes.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Pilih Kelas</label>
                        <select name="course_id" class="form-select rounded-3" required>
                            <option value="">-- Pilih Kelas Diampu --</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->title }} ({{ $course->language }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Topik Pertemuan</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="form-control rounded-3" placeholder="Contoh: Praktik Muhadatsah & Tanya Jawab" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Tanggal &amp; Jam Mulai (WIB)</label>
                        <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}" class="form-control rounded-3" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Durasi (Menit)</label>
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', 60) }}" min="15" max="300" class="form-control rounded-3">
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Tautan Meeting (Google Meet / Zoom)</label>
                        <input type="url" name="meeting_url" value="{{ old('meeting_url') }}" class="form-control rounded-3" placeholder="https://meet.google.com/xxx-xxxx-xxx" required>
                    </div>

                    <button type="submit" class="btn btn-brand fw-semibold w-100 py-2 rounded-3">
                        <i class="bi bi-calendar-plus me-1"></i> Terbitkan Jadwal Live
                    </button>
                </form>
            </div>
        </div>

        <!-- KOLOM KANAN: DAFTAR JADWAL KELAS ONLINE -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Daftar Sesi Kelas Online ({{ $liveClasses->count() }})</h6>
                </div>

                @forelse ($liveClasses as $live)
                    @php
                        $sched = \Carbon\Carbon::parse($live->scheduled_at);
                        $isPast = $sched->copy()->addMinutes($live->duration_minutes ?? 60)->isPast();
                        $isOngoing = !$isPast && $sched->subMinutes(15)->isPast();
                    @endphp

                    <div class="p-3 border rounded-3 mb-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 {{ $isPast ? 'bg-light opacity-75' : '' }}">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-brand-soft text-brand">{{ $live->course->title }}</span>
                                @if ($isOngoing)
                                    <span class="badge bg-danger">Sedang Berlangsung</span>
                                @elseif ($isPast)
                                    <span class="badge bg-secondary">Selesai</span>
                                @else
                                    <span class="badge bg-warning text-dark">Akan Datang</span>
                                @endif
                            </div>

                            <h6 class="fw-bold mb-1">{{ $live->title }}</h6>
                            <small class="text-muted d-block">
                                <i class="bi bi-calendar-event me-1"></i>{{ \Carbon\Carbon::parse($live->scheduled_at)->translatedFormat('l, d M Y • H:i') }} WIB
                                ({{ $live->duration_minutes ?? 60 }} menit)
                            </small>
                        </div>

                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                            <a href="{{ $live->meeting_url }}" target="_blank" class="btn btn-sm {{ $isPast ? 'btn-outline-secondary' : 'btn-brand' }} fw-semibold rounded-3 px-3">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Buka Room
                            </a>
                            <form action="{{ route('teacher.live-classes.destroy', $live->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal kelas online ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-3" title="Hapus Jadwal">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted small">
                        <i class="bi bi-camera-video-off fs-2 d-block mb-2"></i>
                        Belum ada jadwal kelas online. Gunakan formulir di samping untuk membuat sesi tatap muka virtual.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection