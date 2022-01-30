<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;
    protected $table = 'players';
    protected $fillable = ['first_name', 'last_name', 'dni', 'os', 'birthday', 'year', 'email'];
    
    public function team()
    {
        if(isset($_COOKIE['tournament'])) {
            $tournament =  Tournament::find($_COOKIE['tournament']);
        } else {
            $tournament = Tournament::active();
        }
        
        return $this->belongsToMany('App\Models\Team', 'teams_players', 'player_id', 'team_id')
                    ->withPivot('tournament_id')
                    ->wherePivot('tournament_id', $tournament->id)
                    ->first();
    }

    public function teams()
    {
        return $this->belongsToMany('App\Models\Team', 'teams_players', 'player_id', 'team_id')->withPivot('id', 'tournament_id')->using('App\Models\TeamPlayer');
    }
}
