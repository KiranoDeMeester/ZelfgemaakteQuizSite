<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = ['room_id', 'name', 'score'];

    public function room() { return $this->belongsTo(Room::class); }
    public function answers() { return $this->hasMany(PlayerAnswer::class); }
}
