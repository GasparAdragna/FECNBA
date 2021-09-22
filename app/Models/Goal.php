<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;
    protected $table = 'goals';

    public function match()
    {
        return $this->belongsTo('App\Models\Match', 'match_id');
    }
    public function player()
    {
        return $this->belongsTo('App\Models\Player', 'player_id');
    }
    public function team()
    {
        return $this->belongsTo('App\Models\Team', 'team_id');
    }
}
