<?php

use Illuminate\Support\Facades\Route;

// Halaman Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rute sementara untuk Login & Logout (agar tidak RouteNotFoundException)
Route::get('/login', function () {
    return 'Halaman Login JagoBahasa (Dalam Pembuatan)';
})->name('login');

Route::post('/logout', function () {
    return redirect('/');
})->name('logout');