<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = ['course_id', 'title', 'content', 'duration', 'attachment_url', 'order'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function usersCompleted()
    {
        return $this->belongsToMany(User::class, 'lesson_user');
    }
}
