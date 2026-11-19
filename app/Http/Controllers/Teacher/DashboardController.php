<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\LiveClass;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        $courses = $teacher->taughtCourses()->withCount(['students', 'lessons', 'assignments'])->get();
        $courseIds = $courses->pluck('id');

        $totalStudents = $courses->sum('students_count');
        $totalLessons = $courses->sum('lessons_count');

        $assignmentIds = Assignment::whereIn('course_id', $courseIds)->pluck('id');
        $ungradedSubmissions = AssignmentSubmission::with(['student', 'assignment.course'])
            ->whereIn('assignment_id', $assignmentIds)
            ->whereNull('score')
            ->latest('submitted_at')
            ->take(5)
            ->get();

        $pendingGradingCount = AssignmentSubmission::whereIn('assignment_id', $assignmentIds)
            ->whereNull('score')
            ->count();

        $upcomingLiveClasses = LiveClass::with('course')
            ->whereIn('course_id', $courseIds)
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at', 'asc')
            ->take(3)
            ->get();

        return view('teacher.dashboard', compact(
            'courses',
            'totalStudents',
            'totalLessons',
            'pendingGradingCount',
            'ungradedSubmissions',
            'upcomingLiveClasses'
        ));
    }
}