<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    use HasFactory;
    protected $table = 'inscriptions';
    protected $fillable = ['team_id', 'tournament_id', 'name', 'planilla'];

    public function team()
    {
        return $this->belongsTo('App\Models\Team', 'team_id');
    }

    public function tournament()
    {
        return $this->belongsTo('App\Models\Tournament', 'tournament_id');
    }
}
