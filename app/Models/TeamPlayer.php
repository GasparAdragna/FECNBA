<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TeamPlayer extends Pivot
{

    public function tournament()
    {
        return $this->belongsTo('App\Models\Tournament', 'tournament_id');
    }

    public function team()
    {
        return $this->belongsTo('App\Models\Team', 'team_id');
    }
    public function player()
    {
        return $this->belongsTo('App\Models\Player', 'player_id');
    }
}
