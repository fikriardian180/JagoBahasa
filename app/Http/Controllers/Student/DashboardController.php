<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $completedIds = $user->completedLessons()->pluck('lessons.id')->toArray();

        // 1. Ambil seluruh kursus yang diikuti murid beserta progresnya
        $enrolledCourses = $user->enrolledCourses()
            ->with(['teacher', 'lessons'])
            ->withCount('lessons')
            ->get()
            ->map(function ($course) use ($completedIds) {
                $course->completed_lessons_count = $course->lessons
                    ->whereIn('id', $completedIds)
                    ->count();
                $course->progress_percent = $course->lessons_count > 0
                    ? round(($course->completed_lessons_count / $course->lessons_count) * 100)
                    : 0;
                return $course;
            });

        $courseIds = $enrolledCourses->pluck('id')->toArray();

        // 2. Hitung Statistik Utama
        $activeCoursesCount = $enrolledCourses->count();
        $totalModules = $enrolledCourses->sum('lessons_count');
        $completedModules = $enrolledCourses->sum('completed_lessons_count');

        // 3. Cek Tugas Tertunda & Rata-Rata Nilai (aman jika tabel belum dimigrasi)
        $pendingAssignmentsCount = 0;
        $upcomingAssignments = collect();
        $averageGrade = '-';

        if (Schema::hasTable('assignments') && !empty($courseIds)) {
            $submittedIds = Schema::hasTable('assignment_submissions')
                ? DB::table('assignment_submissions')->where('user_id', $user->id)->pluck('assignment_id')->toArray()
                : [];

            $upcomingAssignments = DB::table('assignments')
                ->join('courses', 'assignments.course_id', '=', 'courses.id')
                ->whereIn('assignments.course_id', $courseIds)
                ->whereNotIn('assignments.id', $submittedIds)
                ->select('assignments.*', 'courses.language as course_language', 'courses.title as course_title')
                ->orderBy('assignments.due_date', 'asc')
                ->limit(4)
                ->get();

            $pendingAssignmentsCount = DB::table('assignments')
                ->whereIn('course_id', $courseIds)
                ->whereNotIn('id', $submittedIds)
                ->count();

            if (Schema::hasTable('assignment_submissions')) {
                $avg = DB::table('assignment_submissions')
                    ->where('user_id', $user->id)
                    ->whereNotNull('score')
                    ->avg('score');
                if ($avg !== null) {
                    $averageGrade = number_format($avg, 1);
                }
            }
        }

        // 4. Cek Jadwal Kelas Online Terdekat (aman jika tabel belum dimigrasi)
        $nextLiveClass = null;
        if (Schema::hasTable('live_classes') && !empty($courseIds)) {
            $nextLiveClass = DB::table('live_classes')
                ->join('courses', 'live_classes.course_id', '=', 'courses.id')
                ->whereIn('live_classes.course_id', $courseIds)
                ->where('live_classes.scheduled_at', '>=', now())
                ->select('live_classes.*', 'courses.title as course_title')
                ->orderBy('live_classes.scheduled_at', 'asc')
                ->first();
        }

        return view('student.dashboard', compact(
            'enrolledCourses',
            'activeCoursesCount',
            'totalModules',
            'completedModules',
            'pendingAssignmentsCount',
            'upcomingAssignments',
            'averageGrade',
            'nextLiveClass'
        ));
    }
}