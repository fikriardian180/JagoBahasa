<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{
    public function index()
    {
        $courseIds = Auth::user()->taughtCourses()->pluck('id');

        $assignments = Assignment::with('course')
            ->withCount([
                'submissions',
                'submissions as ungraded_count' => fn ($q) => $q->whereNull('score'),
            ])
            ->whereIn('course_id', $courseIds)
            ->orderBy('due_date', 'desc')
            ->get();

        return view('teacher.assignments.index', compact('assignments'));
    }

    public function create()
    {
        $courses = Auth::user()->taughtCourses()->get();
        return view('teacher.assignments.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:assignment,quiz'],
            'description' => ['nullable', 'string'],
            'due_date' => ['required', 'date'],
            'questions' => ['required_if:type,quiz', 'array'],
            'questions.*.question_text' => ['required_if:type,quiz', 'string'],
            'questions.*.option_a' => ['required_if:type,quiz', 'string'],
            'questions.*.option_b' => ['required_if:type,quiz', 'string'],
            'questions.*.option_c' => ['required_if:type,quiz', 'string'],
            'questions.*.option_d' => ['required_if:type,quiz', 'string'],
            'questions.*.correct_option' => ['required_if:type,quiz', 'in:A,B,C,D'],
        ]);

        $course = Auth::user()->taughtCourses()->findOrFail($validated['course_id']);

        DB::transaction(function () use ($course, $validated) {
            $assignment = $course->assignments()->create([
                'title' => $validated['title'],
                'type' => $validated['type'],
                'description' => $validated['description'] ?? null,
                'due_date' => $validated['due_date'],
            ]);

            if ($validated['type'] === 'quiz' && !empty($validated['questions'])) {
                foreach ($validated['questions'] as $q) {
                    QuizQuestion::create([
                        'assignment_id' => $assignment->id,
                        'question_text' => $q['question_text'],
                        'option_a' => $q['option_a'],
                        'option_b' => $q['option_b'],
                        'option_c' => $q['option_c'],
                        'option_d' => $q['option_d'],
                        'correct_option' => $q['correct_option'],
                    ]);
                }
            }
        });

        return redirect()
            ->route('teacher.assignments.index')
            ->with('success', 'Tugas / Kuis baru berhasil diterbitkan ke murid!');
    }

    public function submissions(Assignment $assignment)
    {
        abort_if($assignment->course->teacher_id !== Auth::id(), 403);

        $assignment->load([
            'course',
            'submissions' => fn ($q) => $q->with('student')->latest('submitted_at'),
        ]);

        return view('teacher.assignments.submissions', compact('assignment'));
    }

    public function grade(Request $request, AssignmentSubmission $submission)
    {
        abort_if($submission->assignment->course->teacher_id !== Auth::id(), 403);

        $validated = $request->validate([
            'score' => ['required', 'numeric', 'min:0', 'max:100'],
            'feedback' => ['nullable', 'string', 'max:1000'],
        ]);

        $submission->update([
            'score' => $validated['score'],
            'feedback' => $validated['feedback'] ?? null,
        ]);

        return back()->with('success', 'Nilai dan umpan balik berhasil disimpan.');
    }

    public function destroy(Assignment $assignment)
    {
        abort_if($assignment->course->teacher_id !== Auth::id(), 403);

        $assignment->delete();

        return back()->with('success', 'Tugas / Kuis berhasil dihapus.');
    }
}