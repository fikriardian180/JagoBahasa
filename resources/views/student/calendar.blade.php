@extends('layouts.student')

@section('title', 'Kalender Jadwal — JagoBahasa')
@section('page_title', 'Kalender Jadwal Belajar')
@section('page_subtitle', 'Kalender tenggat tugas dan jadwal kelas bahasa Anda')

@section('content')
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <h5 class="fw-bold mb-2">Kalender Akademik</h5>
        <p class="text-muted small mb-0">
            Pantau tenggat waktu tugas, kuis, dan jadwal pertemuan secara bulanan.
        </p>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
        <div class="mx-auto bg-brand-soft text-brand rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
            <i class="bi bi-calendar3 fs-2"></i>
        </div>
        <h5 class="fw-bold">Kalender Belajar Sinkron</h5>
        <p class="text-muted small mx-auto mb-0" style="max-width: 400px;">
            Semua jadwal tenggat tugas dan kuis Anda akan otomatis disinkronkan di sini.
        </p>
    </div>
@endsection
