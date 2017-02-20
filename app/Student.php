<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['name', 'age', 'group_id'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function games()
    {
        return $this->belongsToMany(Game::class);
    }

    public function gamesWinned()
    {
        return Game::where('student_winner_id', $this->id)->get();
    }

}
