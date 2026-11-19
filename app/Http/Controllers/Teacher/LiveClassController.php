<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\LiveClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiveClassController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        $courses = $teacher->taughtCourses()->get();
        $courseIds = $courses->pluck('id');

        $liveClasses = LiveClass::with('course')
            ->whereIn('course_id', $courseIds)
            ->orderBy('scheduled_at', 'desc')
            ->get();

        return view('teacher.live-classes.index', compact('courses', 'liveClasses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'scheduled_at' => ['required', 'date'],
            'duration_minutes' => ['nullable', 'integer', 'min:15', 'max:300'],
            'meeting_url' => ['required', 'url'],
        ], [
            'course_id.required' => 'Silakan pilih kelas tujuan.',
            'title.required' => 'Topik pertemuan wajib diisi.',
            'scheduled_at.required' => 'Waktu pelaksanaan wajib ditentukan.',
            'meeting_url.required' => 'Tautan pertemuan (Zoom / Google Meet) wajib diisi.',
            'meeting_url.url' => 'Format tautan pertemuan tidak valid (awali dengan https://).',
        ]);

        // Pastikan kelas dimiliki oleh pengajar yang login
        $course = Auth::user()->taughtCourses()->findOrFail($validated['course_id']);

        LiveClass::create([
            'course_id' => $course->id,
            'title' => $validated['title'],
            'scheduled_at' => $validated['scheduled_at'],
            'duration_minutes' => $validated['duration_minutes'] ?? 60,
            'meeting_url' => $validated['meeting_url'],
        ]);

        return back()->with('success', 'Jadwal kelas online berhasil ditambahkan dan diterbitkan ke murid!');
    }

    public function destroy(LiveClass $liveClass)
    {
        abort_if($liveClass->course->teacher_id !== Auth::id(), 403);

        $liveClass->delete();

        return back()->with('success', 'Jadwal kelas online berhasil dihapus.');
    }
}