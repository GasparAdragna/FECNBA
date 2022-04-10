<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tournament;
use App\Models\Category;

class Team extends Model
{
    use HasFactory;
    protected $table = 'teams';
    protected $fillable = ['name'];

    public function players()
    {
        if(isset($_COOKIE['tournament'])) {
            $tournament =  Tournament::find($_COOKIE['tournament']);
        } else {
            $tournament = Tournament::active();
        }
        
        return $this->belongsToMany('App\Models\Player', 'teams_players', 'team_id', 'player_id')
                    ->withPivot('tournament_id')
                    ->wherePivot('tournament_id', $tournament->id);
    }
    public function categories()
    {
        return $this->belongsToMany('App\Models\Category', 'teams_categories', 'team_id', 'category_id');
    }
    public function category()
    {
        if(isset($_COOKIE['tournament'])) {
            $tournament =  Tournament::find($_COOKIE['tournament']);
        } else {
            $tournament = Tournament::active();
        }
        
        return $this->belongsToMany('App\Models\Category', 'teams_categories', 'team_id', 'category_id')
                    ->withPivot('tournament_id', 'zone')
                    ->wherePivot('tournament_id', $tournament->id)
                    ->first();
    }
}
