<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = ['played_at'];

    public $timestamps = false;

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

    public function moves()
    {
        return $this->hasMany(Move::class);
    }

    public function winner()
    {
        return $this->belongsTo(Student::class, 'student_winner_id');
    }
}