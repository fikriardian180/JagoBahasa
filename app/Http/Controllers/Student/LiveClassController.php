<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\LiveClass;
use Illuminate\Support\Facades\Auth;

class LiveClassController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $courseIds = $user->enrolledCourses()->pluck('courses.id');

        // Sesi mendatang atau sedang berlangsung (batas toleransi 2 jam setelah mulai)
        $upcomingClasses = LiveClass::with(['course.teacher'])
            ->whereIn('course_id', $courseIds)
            ->where('scheduled_at', '>=', now()->subHours(2))
            ->orderBy('scheduled_at', 'asc')
            ->get();

        // Riwayat sesi yang sudah lewat
        $pastClasses = LiveClass::with(['course.teacher'])
            ->whereIn('course_id', $courseIds)
            ->where('scheduled_at', '<', now()->subHours(2))
            ->orderBy('scheduled_at', 'desc')
            ->limit(6)
            ->get();

        return view('student.live-classes', compact('upcomingClasses', 'pastClasses'));
    }
}