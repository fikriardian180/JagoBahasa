<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    public function store(Request $request, Course $course)
    {
        abort_if($course->teacher_id !== Auth::id(), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'media_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,mp3,wav,m4a,jpg,jpeg,png', 'max:10240'], // Maks 10MB
            'media_url' => ['nullable', 'url'],
            'order_number' => ['nullable', 'integer', 'min:1'],
        ]);

        $mediaPath = $validated['media_url'] ?? null;

        if ($request->hasFile('media_file')) {
            $path = $request->file('media_file')->store('lessons', 'public');
            $mediaPath = Storage::url($path); // Menghasilkan /storage/lessons/namafile.ext
        }

        $nextOrder = $validated['order_number'] ?? ($course->lessons()->max('order_number') + 1);

        $course->lessons()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'media_url' => $mediaPath,
            'order_number' => $nextOrder,
        ]);

        return back()->with('success', 'Bab materi baru berhasil ditambahkan ke kelas.');
    }

    public function destroy(Course $course, Lesson $lesson)
    {
        abort_if($course->teacher_id !== Auth::id() || $lesson->course_id !== $course->id, 403);

        if ($lesson->media_url && str_starts_with($lesson->media_url, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $lesson->media_url));
        }

        $lesson->delete();

        return back()->with('success', 'Bab materi berhasil dihapus.');
    }
}