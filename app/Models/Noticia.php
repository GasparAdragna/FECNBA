<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    use HasFactory;
    protected $table = 'noticias';
    protected $fillable = ['titulo', 'resumen', 'texto','estado', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
