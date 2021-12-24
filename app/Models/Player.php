<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;
    protected $table = 'players';
    protected $fillable = ['first_name', 'last_name', 'dni', 'os', 'birthday', 'year', 'email', 'team_id'];
    
    public function team()
    {
        return $this->belongsTo('App\Models\Team', 'team_id');
    }
}
