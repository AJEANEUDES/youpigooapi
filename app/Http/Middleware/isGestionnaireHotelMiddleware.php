<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class isGestionnaireHotelMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Dans notre cas ici, nous allons donner à  un gestionnaire hotel
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() &&  Auth::user() && Auth::user()->roles_user == "Hotel") {
            return $next($request);
        } else {
            // return redirect()->route('login');
         return redirect('/')->with('error', "Vous n'êtes pas un Hôtel");

        }
    }
}
