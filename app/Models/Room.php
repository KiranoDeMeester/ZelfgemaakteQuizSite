<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['code', 'quiz_id', 'status', 'current_question_id'];

    public function quiz() { return $this->belongsTo(Quiz::class); }
    public function players() { return $this->hasMany(Player::class); }
    public function currentQuestion() { return $this->belongsTo(Question::class, 'current_question_id'); }
}
