<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $completedIds = $user->completedLessons()->pluck('lessons.id')->toArray();

        $courses = $user->enrolledCourses()
            ->with(['teacher', 'lessons'])
            ->withCount('lessons')
            ->get()
            ->map(function ($course) use ($completedIds) {
                $course->completed_lessons_count = $course->lessons
                    ->whereIn('id', $completedIds)
                    ->count();
                return $course;
            });

        return view('student.courses.index', compact('courses'));
    }

    public function join(Request $request)
    {
        $request->validate(['class_code' => 'required|string']);

        $course = Course::where('class_code', strtoupper(trim($request->class_code)))->first();

        if (!$course) {
            return back()->with('error', 'Kode kelas tidak ditemukan. Periksa kembali kode dari pengajar Anda.');
        }

        $user = Auth::user();
        if ($user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            return back()->with('error', 'Anda sudah tergabung di kelas ' . $course->title . '.');
        }

        $user->enrolledCourses()->attach($course->id);

        return redirect()->route('student.courses.show', $course->id)
            ->with('success', 'Berhasil bergabung ke kelas ' . $course->title . '!');
    }

    public function show(Course $course)
    {
        $user = Auth::user();
        $completedIds = $user->completedLessons()->pluck('lessons.id')->toArray();

        $course->load(['teacher', 'lessons']);
        foreach ($course->lessons as $lesson) {
            $lesson->is_completed = in_array($lesson->id, $completedIds);
        }

        return view('student.courses.show', compact('course'));
    }

    public function completeLesson(Lesson $lesson)
    {
        Auth::user()->completedLessons()->syncWithoutDetaching([$lesson->id]);

        return back()->with('success', 'Modul "' . $lesson->title . '" ditandai selesai!');
    }
}