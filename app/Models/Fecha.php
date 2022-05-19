<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fecha extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'tournament_id', 'dia', 'active'];
    protected $casts = [
        'active' => 'boolean',
    ];

    public function matches()
    {
        return $this->hasMany('App\Models\Match', 'fecha_id', 'id');
    }
        public function matchesForDashboard()
    {
        return $this->hasMany('App\Models\Match', 'fecha_id', 'id')->get()->shuffle()->take(20);
    }
    public function tournament()
    {
        return $this->belongsTo('App\Models\Tournament', 'tournament_id');
    }

}
