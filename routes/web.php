<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\CourseController;
use App\Http\Controllers\Student\AssignmentController;
use App\Http\Controllers\Student\LiveClassController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Teacher\CourseController as TeacherCourseController;
use App\Http\Controllers\Teacher\LessonController as TeacherLessonController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\ProfileController as TeacherProfileController;
use App\Http\Controllers\Teacher\AssignmentController as TeacherAssignmentController;
use App\Http\Controllers\Teacher\LiveClassController as TeacherLiveClassController;

Route::get('/', function () {
    return view('welcome');
});

// Autentikasi (Login, Register, Logout)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Portal Murid
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::post('/courses/join', [CourseController::class, 'join'])->name('courses.join');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::post('/lessons/{lesson}/complete', [CourseController::class, 'completeLesson'])->name('lessons.complete');
    Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/assignments/{assignment}', [AssignmentController::class, 'show'])->name('assignments.show');
    Route::post('/assignments/{assignment}/submit', [AssignmentController::class, 'submit'])->name('assignments.submit');
    Route::get('/live-classes', [LiveClassController::class, 'index'])->name('live-classes');
    Route::get('/calendar', fn() => view('student.calendar'))->name('calendar');
    Route::get('/grades', fn() => view('student.grades'))->name('grades');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/info', [ProfileController::class, 'updateInfo'])->name('profile.update-info');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
});

// Portal Guru
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');

    // Manajemen Kelas & Materi
    Route::resource('courses', TeacherCourseController::class)->except(['edit', 'update']);
    Route::post('/courses/{course}/lessons', [TeacherLessonController::class, 'store'])->name('courses.lessons.store');
    Route::delete('/courses/{course}/lessons/{lesson}', [TeacherLessonController::class, 'destroy'])->name('courses.lessons.destroy');

    // Placeholder sementara sebelum Controller Tugas & Live Class Guru dibuat
    Route::get('/assignments', [TeacherAssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/assignments/create', [TeacherAssignmentController::class, 'create'])->name('assignments.create');
    Route::post('/assignments', [TeacherAssignmentController::class, 'store'])->name('assignments.store');
    Route::get('/assignments/{assignment}/submissions', [TeacherAssignmentController::class, 'submissions'])->name('assignments.submissions');
    Route::put('/submissions/{submission}/grade', [TeacherAssignmentController::class, 'grade'])->name('submissions.grade');
    Route::delete('/assignments/{assignment}', [TeacherAssignmentController::class, 'destroy'])->name('assignments.destroy');
    Route::get('/live-classes', [TeacherLiveClassController::class, 'index'])->name('live-classes.index');
    Route::post('/live-classes', [TeacherLiveClassController::class, 'store'])->name('live-classes.store');
    Route::delete('/live-classes/{liveClass}', [TeacherLiveClassController::class, 'destroy'])->name('live-classes.destroy');

    Route::get('/profile', [TeacherProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/info', [TeacherProfileController::class, 'updateInfo'])->name('profile.update-info');
    Route::put('/profile/password', [TeacherProfileController::class, 'updatePassword'])->name('profile.update-password');
});
