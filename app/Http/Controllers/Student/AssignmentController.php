<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $courseIds = $user->enrolledCourses()->pluck('courses.id');

        $assignments = Assignment::with(['course', 'submissions' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }])
            ->whereIn('course_id', $courseIds)
            ->orderBy('due_date', 'asc')
            ->get();

        return view('student.assignments.index', compact('assignments'));
    }

    public function show(Assignment $assignment)
    {
        $user = Auth::user();

        // Pastikan murid terdaftar di kursus terkait
        abort_unless($user->enrolledCourses()->where('courses.id', $assignment->course_id)->exists(), 403);

        $assignment->load(['course', 'questions']);
        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('user_id', $user->id)
            ->first();

        return view('student.assignments.show', compact('assignment', 'submission'));
    }

    public function submit(Request $request, Assignment $assignment)
    {
        $user = Auth::user();

        // Cegah pengiriman ulang jika sudah dikumpulkan
        $existing = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah mengumpulkan tugas/kuis ini sebelumnya.');
        }

        // Jika bertipe KUIS (Hitung Nilai Otomatis)
        if ($assignment->type === 'quiz') {
            $questions = $assignment->questions;
            $answers = $request->input('answers', []);
            $correctCount = 0;

            foreach ($questions as $q) {
                if (isset($answers[$q->id]) && strtolower($answers[$q->id]) === strtolower($q->correct_option)) {
                    $correctCount++;
                }
            }

            $totalQuestions = $questions->count();
            $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100, 2) : 0;

            AssignmentSubmission::create([
                'assignment_id' => $assignment->id,
                'user_id' => $user->id,
                'answer_text' => json_encode($answers),
                'score' => $score,
                'submitted_at' => now(),
            ]);

            return redirect()->route('student.assignments.show', $assignment->id)
                ->with('success', "Kuis selesai! Anda menjawab benar {$correctCount} dari {$totalQuestions} soal (Nilai: {$score}).");
        }

        // Jika bertipe TUGAS PRAKTIK (Writing / Speaking Audio Link)
        $request->validate([
            'answer_text' => 'required|string|min:5',
            'attachment_url' => 'nullable|url',
        ]);

        AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'user_id' => $user->id,
            'answer_text' => $request->answer_text,
            'attachment_url' => $request->attachment_url,
            'submitted_at' => now(),
        ]);

        return redirect()->route('student.assignments.show', $assignment->id)
            ->with('success', 'Tugas berhasil dikumpulkan dan menunggu penilaian pengajar.');
    }
}