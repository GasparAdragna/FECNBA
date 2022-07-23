<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;
    protected $table = 'zones';
    protected $fillable = ['name', 'category_id', 'tournament_id'];

    public function Category() {
        return $this->belongsTo('App\Models\Category');
    }

    public function Tournament()
    {
        return $this->belongsTo('App\Models\Tournament');
    }
}
