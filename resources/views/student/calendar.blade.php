@extends('layouts.student')

@section('title', __('messages.calendar_page_title') . ' — JagoBahasa')
@section('page_title', __('messages.calendar_header_title'))
@section('page_subtitle', __('messages.calendar_header_sub'))

@section('content')
    <div class="card border-0 shadow-sm rounded-4 p-3 p-sm-4 mb-3 mb-sm-4">
        <h5 class="fw-bold mb-2">{{ __('messages.calendar_card_title') }}</h5>
        <p class="text-muted small mb-0" style="line-height: 1.65;">
            {{ __('messages.calendar_card_desc') }}
        </p>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4 p-sm-5 text-center">
        <div class="mx-auto bg-brand-soft text-brand rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
            <i class="bi bi-calendar3 fs-3"></i>
        </div>
        <h5 class="fw-bold">{{ __('messages.calendar_sync_title') }}</h5>
        <p class="text-muted small mx-auto mb-0" style="max-width: 400px; line-height: 1.65;">
            {{ __('messages.calendar_sync_desc') }}
        </p>
    </div>
@endsection