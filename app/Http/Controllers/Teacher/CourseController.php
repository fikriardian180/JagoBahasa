<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Auth::user()
            ->taughtCourses()
            ->withCount(['students', 'lessons', 'assignments'])
            ->latest()
            ->get();

        return view('teacher.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('teacher.courses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'language' => ['required', 'in:Bahasa Inggris,Bahasa Arab'],
            'level' => ['required', 'in:Pemula (Beginner),Menengah (Intermediate),Mahir (Advanced)'],
            'description' => ['nullable', 'string'],
        ]);

        // Buat kode kelas unik otomatis: JGB-EN48 atau JGB-AR92
        $prefix = $validated['language'] === 'Bahasa Arab' ? 'JGB-AR' : 'JGB-EN';
        do {
            $classCode = $prefix . strtoupper(Str::random(4));
        } while (Course::where('class_code', $classCode)->exists());

        $course = Course::create([
            'teacher_id' => Auth::id(),
            'title' => $validated['title'],
            'language' => $validated['language'],
            'level' => $validated['level'],
            'description' => $validated['description'],
            'class_code' => $classCode,
        ]);

        return redirect()
            ->route('teacher.courses.show', $course->id)
            ->with('success', "Kelas berhasil dibuat! Bagikan kode kelas {$classCode} kepada murid Anda.");
    }

    public function show(Course $course)
    {
        abort_if($course->teacher_id !== Auth::id(), 403);

        $course->load([
            'lessons' => fn ($q) => $q->orderBy('order_number', 'asc')->orderBy('id', 'asc'),
            'students',
            'assignments' => fn ($q) => $q->withCount('submissions')->orderBy('due_date', 'asc'),
        ]);

        return view('teacher.courses.show', compact('course'));
    }

    public function destroy(Course $course)
    {
        abort_if($course->teacher_id !== Auth::id(), 403);

        $course->delete();

        return redirect()
            ->route('teacher.courses.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}