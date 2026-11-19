<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = User::where('role', 'student')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalCourses = Course::count();
        $totalAssignments = Assignment::count();
        $averageScore = AssignmentSubmission::whereNotNull('score')->avg('score');

        $recentUsers = User::whereIn('role', ['student', 'teacher'])
            ->latest()
            ->take(5)
            ->get();

        $courses = Course::with('teacher')
            ->withCount(['students', 'lessons', 'assignments'])
            ->latest()
            ->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalTeachers',
            'totalCourses',
            'totalAssignments',
            'averageScore',
            'recentUsers',
            'courses'
        ));
    }

    public function users(Request $request)
    {
        $roleFilter = $request->query('role', 'all');
        $search = $request->query('search');

        $usersQuery = User::whereIn('role', ['student', 'teacher'])
            ->withCount(['enrolledCourses', 'taughtCourses']);

        if (in_array($roleFilter, ['student', 'teacher'])) {
            $usersQuery->where('role', $roleFilter);
        }

        if ($search) {
            $usersQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $usersQuery->latest()->get();

        return view('admin.users', compact('users', 'roleFilter', 'search'));
    }

    public function destroyUser(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Akun administrator tidak dapat dihapus.');
        }

        $user->delete();

        return back()->with('success', "Akun {$user->name} ({$user->role}) berhasil dihapus.");
    }

    public function grades(Request $request)
    {
        $search = $request->query('search');

        $studentsQuery = User::where('role', 'student')
            ->with(['enrolledCourses', 'submissions.assignment.course']);

        if ($search) {
            $studentsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $studentsQuery->orderBy('name')->get()->map(function ($student) {
            $gradedSubmissions = $student->submissions->whereNotNull('score');
            $student->total_submissions = $student->submissions->count();
            $student->graded_count = $gradedSubmissions->count();
            $student->average_score = $gradedSubmissions->count() > 0
                ? round($gradedSubmissions->avg('score'), 1)
                : null;

            return $student;
        });

        return view('admin.grades', compact('students', 'search'));
    }
}