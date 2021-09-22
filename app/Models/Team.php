<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    protected $table = 'teams';

    public function players()
    {
        return $this->hasMany('App\Models\Player', 'team_id', 'id');
    }
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

}
