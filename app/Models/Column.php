<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
    use HasFactory;
    
    protected $table = 'columns';
    protected $fillable = ['titulo', 'resumen', 'texto', 'descripcion', 'autor', 'category_id', 'tournament_id', 'photo', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function categoria()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
}
