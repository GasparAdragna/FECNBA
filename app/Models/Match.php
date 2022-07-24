<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    use HasFactory;
    protected $table = 'matches';
    protected $fillable = ['tournament_id', 'category_id','fecha_id', 'team_id_1', 'team_id_2', 'cancha', 'horario', 'zone_id', 'finished'];
    protected $casts = [
        'finished' => 'boolean',
    ];
    
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
    public function tournament()
    {
        return $this->belongsTo('App\Models\Tournament');
    }
    public function fecha()
    {
        return $this->belongsTo('App\Models\Fecha', 'fecha_id');
    }
    public function local()
    {
        return $this->hasOne('App\Models\Team', 'id', 'team_id_1');
    }
    public function visita()
    {
        return $this->hasOne('App\Models\Team', 'id', 'team_id_2');
    }
    public function goles()
    {
        return $this->hasMany('App\Models\Goal');
    }
    public function zone()
    {
        return $this->belongsTo('App\Models\Zone', 'zone_id');
    }
}
