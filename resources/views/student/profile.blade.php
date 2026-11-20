@extends('layouts.student')

@section('title', __('messages.student_my_profile') . ' — JagoBahasa')
@section('page_title', __('messages.student_profile_header_title'))
@section('page_subtitle', __('messages.student_profile_header_sub'))

@section('content')
    <div class="row g-3 g-lg-4">
        <!-- KOLOM KIRI: RINGKASAN PROFIL -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 p-sm-4 text-center">
                <div class="mx-auto bg-brand text-white fw-extrabold rounded-circle d-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 76px; height: 76px; font-size: 1.9rem;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h5 class="fw-bold mb-1 text-break">{{ $user->name }}</h5>
                <p class="text-muted small mb-3 text-break">{{ $user->email }}</p>

                <div class="d-inline-flex justify-content-center mb-3">
                    <span class="badge bg-brand-soft text-brand border border-success border-opacity-25 px-3 py-2 rounded-pill">
                        <i class="bi bi-person-badge me-1"></i> {{ __('messages.student_profile_role_badge') }}
                    </span>
                </div>

                <div class="border-top pt-3 mt-2 text-start small text-muted">
                    <div class="d-flex flex-wrap justify-content-between gap-1 mb-2">
                        <span>{{ __('messages.profile_joined_since') }}</span>
                        <span class="fw-semibold text-dark">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="d-flex flex-wrap justify-content-between gap-1">
                        <span>{{ __('messages.student_profile_classes_enrolled') }}</span>
                        <span class="fw-semibold text-dark">{{ $user->enrolledCourses()->count() }} {{ __('messages.unit_classes') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: FORM EDIT EMAIL & PASSWORD -->
        <div class="col-lg-8 d-flex flex-column gap-3 gap-lg-4">
            <!-- 1. FORM UBAH NAMA & EMAIL -->
            <div class="card border-0 shadow-sm rounded-4 p-3 p-sm-4">
                <div class="d-flex align-items-center gap-2 gap-sm-3 mb-3 pb-2 border-bottom">
                    <div class="bg-brand-soft text-brand rounded-3 p-2 flex-shrink-0">
                        <i class="bi bi-person-lines-fill fs-5"></i>
                    </div>
                    <div class="min-w-0">
                        <h6 class="fw-bold mb-0">{{ __('messages.profile_basic_info_title') }}</h6>
                        <small class="text-muted d-block">{{ __('messages.profile_basic_info_sub') }}</small>
                    </div>
                </div>

                <form action="{{ route('student.profile.update-info') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label small fw-semibold">{{ __('messages.auth_fullname_label') }}</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-control rounded-3 @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label small fw-semibold">{{ __('messages.auth_email_label') }}</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control rounded-3 @error('email') is-invalid @enderror" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid d-sm-block text-sm-end">
                        <button type="submit" class="btn btn-brand fw-semibold px-4 py-2 rounded-3">
                            <i class="bi bi-check2-circle me-1"></i> {{ __('messages.profile_btn_save_info') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- 2. FORM UBAH KATA SANDI -->
            <div class="card border-0 shadow-sm rounded-4 p-3 p-sm-4">
                <div class="d-flex align-items-center gap-2 gap-sm-3 mb-3 pb-2 border-bottom">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-2 flex-shrink-0">
                        <i class="bi bi-shield-lock-fill fs-5"></i>
                    </div>
                    <div class="min-w-0">
                        <h6 class="fw-bold mb-0">{{ __('messages.profile_password_title') }}</h6>
                        <small class="text-muted d-block">{{ __('messages.student_profile_password_sub') }}</small>
                    </div>
                </div>

                <form action="{{ route('student.profile.update-password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label small fw-semibold">{{ __('messages.profile_current_password') }}</label>
                        <input type="password" name="current_password" id="current_password" class="form-control rounded-3 @error('current_password') is-invalid @enderror" placeholder="{{ __('messages.profile_current_password_placeholder') }}" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="password" class="form-label small fw-semibold">{{ __('messages.profile_new_password') }}</label>
                            <input type="password" name="password" id="password" class="form-control rounded-3 @error('password') is-invalid @enderror" placeholder="{{ __('messages.student_profile_password_placeholder') }}" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label small fw-semibold">{{ __('messages.profile_confirm_new_password') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control rounded-3" placeholder="{{ __('messages.profile_confirm_new_password_placeholder') }}" required>
                        </div>
                    </div>

                    <div class="d-grid d-sm-block text-sm-end">
                        <button type="submit" class="btn btn-outline-brand fw-semibold px-4 py-2 rounded-3">
                            <i class="bi bi-key-fill me-1"></i> {{ __('messages.profile_btn_update_password') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection