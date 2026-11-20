@extends('layouts.student')

@section('title', __('messages.nav_live_classes') . ' — JagoBahasa')
@section('page_title', __('messages.live_header_title'))
@section('page_subtitle', __('messages.live_header_sub'))

@section('content')
    <div class="card border-0 shadow-sm rounded-4 p-3 p-sm-4 mb-3 mb-sm-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h5 class="fw-bold mb-1">{{ __('messages.live_card_title') }}</h5>
                <p class="text-muted small mb-0" style="line-height: 1.65;">
                    {!! __('messages.live_card_desc_html') !!}
                </p>
            </div>
            <span class="badge bg-brand-soft text-brand px-3 py-2 rounded-3 align-self-start align-self-md-center flex-shrink-0">
                <i class="bi bi-broadcast me-1"></i> {{ __('messages.live_scheduled_count', ['count' => $upcomingClasses->count()]) }}
            </span>
        </div>
    </div>

    <!-- SESI MENDATANG / SEDANG BERLANGSUNG -->
    <div class="row g-3 g-sm-4 mb-4 mb-sm-5">
        @forelse ($upcomingClasses as $session)
            @php
                // Dianggap sedang berlangsung jika waktu mulai sudah lewat <= 2 jam atau tinggal <= 15 menit lagi
                $isLiveNow = now()->between($session->scheduled_at->copy()->subMinutes(15), $session->scheduled_at->copy()->addMinutes($session->duration_minutes));
            @endphp

            <div class="col-12 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 p-sm-4 h-100 d-flex flex-column {{ $isLiveNow ? 'border-start border-4 border-success' : '' }}">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                        <span class="badge bg-brand-soft text-brand">{{ $session->course->language }}</span>
                        @if ($isLiveNow)
                            <span class="badge bg-danger rounded-pill px-3 flex-shrink-0">
                                <i class="bi bi-record-circle me-1"></i>{{ __('messages.live_status_now') }}
                            </span>
                        @else
                            <span class="badge bg-light text-dark border rounded-pill px-3 flex-shrink-0">
                                {{ $session->scheduled_at->locale(app()->getLocale())->diffForHumans() }}
                            </span>
                        @endif
                    </div>

                    <h5 class="fw-bold mb-1 fs-6 fs-sm-5">{{ $session->title }}</h5>
                    <small class="text-muted d-block mb-3">
                        {{ __('messages.live_class_label') }}: <strong>{{ $session->course->title }}</strong> &bull; {{ __('messages.student_instructor_label') }}: {{ $session->course->teacher->name ?? __('messages.student_instructor_label') }}
                    </small>

                    @if ($session->description)
                        <p class="text-muted small mb-3" style="line-height: 1.6;">{{ $session->description }}</p>
                    @endif

                    <div class="p-3 rounded-3 bg-light border small mb-3 mb-sm-4 mt-auto">
                        <div class="d-flex flex-wrap justify-content-between gap-1 mb-2">
                            <span class="text-muted"><i class="bi bi-calendar-event me-1"></i>{{ __('messages.live_datetime_label') }}:</span>
                            <span class="fw-semibold">{{ $session->scheduled_at->format('d M Y, H:i') }} WIB</span>
                        </div>
                        <div class="d-flex flex-wrap justify-content-between gap-1">
                            <span class="text-muted"><i class="bi bi-camera-video me-1"></i>{{ __('messages.live_platform_duration_label') }}:</span>
                            <span class="fw-semibold">{{ $session->platform }} ({{ $session->duration_minutes }} {{ __('messages.unit_minutes') }})</span>
                        </div>
                    </div>

                    <a href="{{ $session->meeting_url }}" target="_blank" rel="noopener noreferrer" class="btn {{ $isLiveNow ? 'btn-brand' : 'btn-outline-brand' }} fw-semibold w-100 rounded-3 py-2">
                        <i class="bi bi-box-arrow-up-right me-1"></i> {{ __('messages.live_btn_join_meeting') }}
                    </a>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-sm-5 text-center">
                    <div class="mx-auto bg-brand-soft text-brand rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-camera-video fs-3"></i>
                    </div>
                    <h5 class="fw-bold">{{ __('messages.live_empty_title') }}</h5>
                    <p class="text-muted small mx-auto mb-0" style="max-width: 400px; line-height: 1.65;">
                        {{ __('messages.live_empty_desc') }}
                    </p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- RIWAYAT SESI YANG SUDAH SELESAI -->
    @if ($pastClasses->isNotEmpty())
        <h6 class="fw-bold text-muted mb-3">{{ __('messages.live_past_heading') }}</h6>

        <!-- Desktop: Tabel -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden d-none d-md-block">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">{{ __('messages.live_col_topic') }}</th>
                            <th>{{ __('messages.grades_col_course') }}</th>
                            <th>{{ __('messages.live_col_time') }}</th>
                            <th class="text-end pe-4">{{ __('messages.live_col_status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pastClasses as $past)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $past->title }}</td>
                                <td>{{ $past->course->title }}</td>
                                <td class="text-muted">{{ $past->scheduled_at->format('d M Y, H:i') }} WIB</td>
                                <td class="text-end pe-4"><span class="badge bg-secondary bg-opacity-10 text-secondary">{{ __('messages.live_status_completed') }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Smartphone: Kartu Ringkas -->
        <div class="d-md-none d-flex flex-column gap-2">
            @foreach ($pastClasses as $past)
                <div class="card border-0 shadow-sm rounded-4 p-3">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                        <div class="fw-bold small lh-sm">{{ $past->title }}</div>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary flex-shrink-0">{{ __('messages.live_status_completed') }}</span>
                    </div>
                    <small class="text-muted d-block text-truncate mb-1">
                        <i class="bi bi-journal-bookmark me-1"></i>{{ $past->course->title }}
                    </small>
                    <small class="text-muted" style="font-size: 0.78rem;">
                        <i class="bi bi-clock me-1"></i>{{ $past->scheduled_at->format('d M Y, H:i') }} WIB
                    </small>
                </div>
            @endforeach
        </div>
    @endif
@endsection