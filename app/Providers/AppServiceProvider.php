<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Paksa skema HTTPS saat berjalan di production atau di balik reverse proxy Cloudflare
        if ($this->app->environment('production') || request()->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }

        // Otomatis mengirim jumlah kursus, tugas tertunda, & notifikasi ke layout murid
        View::composer('layouts.student', function ($view) {
            $enrolledCoursesCount = 0;
            $pendingTasksCount = 0;
            $notifications = collect();

            if (Auth::check()) {
                $user = Auth::user();
                $courseIds = $user->enrolledCourses()->pluck('courses.id')->toArray();
                $enrolledCoursesCount = count($courseIds);

                if (!empty($courseIds)) {
                    // 1. Hitung & ambil notifikasi Tugas / Kuis yang belum dikerjakan
                    if (Schema::hasTable('assignments')) {
                        $submittedIds = Schema::hasTable('assignment_submissions')
                            ? DB::table('assignment_submissions')
                                ->where('user_id', $user->id)
                                ->pluck('assignment_id')
                                ->toArray()
                            : [];

                        $pendingTasks = DB::table('assignments')
                            ->join('courses', 'assignments.course_id', '=', 'courses.id')
                            ->whereIn('assignments.course_id', $courseIds)
                            ->whereNotIn('assignments.id', $submittedIds)
                            ->select(
                                'assignments.id',
                                'assignments.title',
                                'assignments.type',
                                'assignments.due_date',
                                'courses.title as course_title'
                            )
                            ->orderBy('assignments.due_date', 'asc')
                            ->get();

                        $pendingTasksCount = $pendingTasks->count();

                        foreach ($pendingTasks->take(3) as $task) {
                            $due = Carbon::parse($task->due_date);
                            $notifications->push([
                                'icon' => $task->type === 'quiz' ? 'bi-ui-checks' : 'bi-clipboard2-check',
                                'color' => 'text-warning',
                                'title' => ($task->type === 'quiz' ? 'Kuis Baru: ' : 'Tugas Baru: ') . $task->title,
                                'subtitle' => $task->course_title . ' • Tenggat ' . $due->diffForHumans(),
                                'url' => route('student.assignments.show', $task->id),
                            ]);
                        }
                    }

                    // 2. Ambil notifikasi Kelas Online terdekat (dalam 24 jam ke depan)
                    if (Schema::hasTable('live_classes')) {
                        $upcomingLives = DB::table('live_classes')
                            ->join('courses', 'live_classes.course_id', '=', 'courses.id')
                            ->whereIn('live_classes.course_id', $courseIds)
                            ->whereBetween('live_classes.scheduled_at', [now()->subMinutes(30), now()->addDay()])
                            ->select(
                                'live_classes.title',
                                'live_classes.scheduled_at',
                                'courses.title as course_title'
                            )
                            ->orderBy('live_classes.scheduled_at', 'asc')
                            ->limit(2)
                            ->get();

                        foreach ($upcomingLives as $live) {
                            $sched = Carbon::parse($live->scheduled_at);
                            $notifications->push([
                                'icon' => 'bi-camera-video-fill',
                                'color' => 'text-brand',
                                'title' => 'Kelas Live: ' . $live->title,
                                'subtitle' => $live->course_title . ' • ' . $sched->format('H:i') . ' WIB (' . $sched->diffForHumans() . ')',
                                'url' => route('student.live-classes'),
                            ]);
                        }
                    }
                }
            }

            $view->with([
                'sidebarCoursesCount' => $enrolledCoursesCount,
                'sidebarPendingTasksCount' => $pendingTasksCount,
                'studentNotifications' => $notifications,
            ]);
        });
    }
}