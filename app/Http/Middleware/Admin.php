<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) return redirect('login');

        $user = Auth::user();
        if($user->isAdmin()) return $next($request);

        return redirect('login')->with('error', 'No tienes permisos para acceder a esta página');
    }
}
