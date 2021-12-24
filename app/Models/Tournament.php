<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    public function fechas()
    {
        return $this->hasMany('App\Models\Fecha', 'tournament_id');
    }
    public static function active()
    {
        if(isset($_COOKIE['tournament'])) {
            return Tournament::find($_COOKIE['tournament']);
        }

        return Tournament::where('active', true)->first();
    }
}
