<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;
    
    protected $fillable = ['state', 'text', 'color', 'icon'];
    protected $casts = [
        'active' => 'boolean',
    ];
}
