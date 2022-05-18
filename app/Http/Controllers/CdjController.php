<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Column;
use App\Models\Tournament;
use Illuminate\Http\Request;

class CdjController extends Controller
{
    public function index()
    {
        $columnas = Column::all();
        $categorias = Category::all();
        $torneos = Tournament::all();
        return view('cdj.index', compact('columnas', 'categorias', 'torneos'));
    }
}
