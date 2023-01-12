<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {

            // if (Auth::guard($guard)->check()) {
            //     return redirect(RouteServiceProvider::HOME);
            // }

            // Permission pour se connecter en tant que client

            if (Auth::guard($guard)->check() && Auth::user()->roles_user == "Client") 
            
            {

                return response()->json(['status' => 'Connecté en tant que Client']);
                // return redirect()->route('utilisateur.tableaudebord');
            }

            // Permission pour se connecter en tant que hotel

            elseif (Auth::guard($guard)->check() && Auth::user()->roles_user == "Hotel")
           
            {
                return response()->json(['status' => "Connecté en tant qu'Hôtel"]);

                //  return redirect()->route('hotel.tableaudebord');

            }


            // Permission pour se connecter en tant que admin

            elseif (Auth::guard($guard)->check() && Auth::user()->roles_user == "Admin")
            {
                
                return response()->json(['status' => "Connecté en tant qu'Admin"]);

                // return redirect()->route('admin.tableaudebord');

            }
            

             
            // Permission pour se connecter en tant que admin

            elseif (Auth::guard($guard)->check() && Auth::user()->roles_user == "Superadmin")
            {
                
                return response()->json(['status' => "Connecté en tant que Super Admin"]);

                // return redirect()->route('superadmin.tableaudebord');

            }
            

              // Permission pour se connecter en tant que compagnie

              elseif (Auth::guard($guard)->check() && Auth::user()->roles_user == "Compagnie")
              {
                  
                  return response()->json(['status' => "Connecté en tant que Compagnie"]);
  
                  // return redirect()->route('superadmin.tableaudebord');
  
              }


        }

        return $next($request);
    }
}
