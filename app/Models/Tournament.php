<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    public function fechas()
    {
        return $this->hasMany('App\Models\Fecha', 'tournament_id', 'id');
    }
    public static function active()
    {
        if(isset($_COOKIE['tournament'])) {
            return Tournament::find($_COOKIE['tournament']);
        }

        return Tournament::where('active', true)->first();
    }
    public function equiposActivos()
    {
       return  $this->belongsToMany('App\Models\Team', 'teams_categories', 'tournament_id', 'team_id')
                    ->withPivot('tournament_id', 'zone')->orderBy('name', 'asc')->get();
    }
    public function equipos($id)
    {
        return  $this->belongsToMany('App\Models\Team', 'teams_categories', 'tournament_id', 'team_id')
                    ->wherePivot('category_id', $id)->orderBy('name', 'asc')->get();
    }
}
