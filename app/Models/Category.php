<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable = ['name'];

    public function equipos()
    {
        return $this->belongsToMany(Team::class, 'teams_categories', 'category_id', 'team_id');
    }
    public function zonas()
    {
        if(isset($_COOKIE['tournament'])) {
            $tournament =  Tournament::find($_COOKIE['tournament']);
        } else {
            $tournament = Tournament::active();
        }
        
        return $this->hasMany(Zone::class, 'category_id')->where('tournament_id', $tournament->id);
    }
    
}
