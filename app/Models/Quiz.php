<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    /**
     * A quiz can have many questions.
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

     /**
     * A quiz can be assigned to many users (through assigned quizzes).
     */
    public function assignedQuizzes()
    {
        return $this->hasMany(QuizAssignment::class);
    }
    
}
