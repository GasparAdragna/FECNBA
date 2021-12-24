<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';

    public function equipos()
    {
        return $this->belongsToMany(Team::class, 'teams_categories', 'category_id', 'team_id');
    }
    
}
