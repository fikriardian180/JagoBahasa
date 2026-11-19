<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['teacher_id', 'title', 'language', 'class_code', 'description'];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'course_user');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class)->orderBy('due_date', 'asc');
    }

    public function liveClasses()
    {
        return $this->hasMany(LiveClass::class)->orderBy('scheduled_at', 'asc');
    }
}
