<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sanction extends Model
{
    use HasFactory;
    protected $table = 'sanctions';
    protected $fillable = ['name', 'motive', 'active', 'player_id', 'team_id', 'tournament_id', 'fecha_id', 'category_id', 'sanction'];
    protected $casts = [
        'active' => 'boolean',
    ];

    public function team()
    {
        return $this->belongsTo('App\Models\Team', 'team_id');
    }
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
    public function tournament()
    {
        return $this->belongsTo('App\Models\Tournament', 'tournament_id');
    }
    public function player()
    {
        return $this->belongsTo('App\Models\Player', 'player_id');
    }
    public function fecha()
    {
        return $this->belongsTo('App\Models\Fecha', 'fecha_id');
    }
}
