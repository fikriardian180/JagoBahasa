<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function store(Request $request, Course $course)
    {
        abort_if($course->teacher_id !== Auth::id(), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'media_url' => ['nullable', 'url'],
            'order_number' => ['nullable', 'integer', 'min:1'],
        ]);

        $nextOrder = $validated['order_number'] ?? ($course->lessons()->max('order_number') + 1);

        $course->lessons()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'media_url' => $validated['media_url'] ?? null,
            'order_number' => $nextOrder,
        ]);

        return back()->with('success', 'Bab materi baru berhasil ditambahkan ke kelas.');
    }

    public function destroy(Course $course, Lesson $lesson)
    {
        abort_if($course->teacher_id !== Auth::id() || $lesson->course_id !== $course->id, 403);

        $lesson->delete();

        return back()->with('success', 'Bab materi berhasil dihapus.');
    }
}