<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $fillable = ['assignment_id', 'question', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_option'];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
}