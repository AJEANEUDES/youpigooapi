<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // protected function redirectTo()
    // {
    //     //condition

    //     if(Auth::user()->roles->pluck('name')->contains('SuperAdmin'))
    //     {
    //         return '/admin/tableaudebord';
    //     }

    //     elseif(Auth::user()->roles->pluck('name')->contains('admin'))
    //     {
    //         return '/admin/users';
            
    //     }

    //     else{
    //         return '/admin/tableaudebord';
    //     }
        
    // }


    public function redirectTo()
    {
        $session = Session::all();


        //Session de connexion du client 


        if(Auth()->user()->roles_user == "Client")
        {
            if(!array_key_exists('details_url_previous', $session)){
                return route('utilisateur.tableaudebord');
            }

            else
            {
                return $session['details_url_previous'];
            }

        }


        //session de la connexion de l'Hôtel

        elseif(Auth()->user()->roles_user == "Hotel")
        {
            return route('hotel.tableaudebord');
        }

        
        //session de la connexion de l'admin


        elseif(Auth()->user()->roles_user == "Admin")
        {
            return route('admin.tableaudebord');
        }


         
        //session de la connexion du superadmin


        elseif(Auth()->user()->roles_user == "Superadmin")
        {
            return route('superadmin.tableaudebord');
        }


    }


    //Methode pour se connecter



    public function login(Request $request)
    {
        $messages = [
            "email.required" => "Votre adresse mail est requis",
            "email.exists" => "Vous n'avez pas de compte veuillez creer un compte.",
            "password.required" => "Votre mot de passe est requis",
        ];

        
        $validator = Validator::make($request->all(), [
            'email' => 'bail|required|exists:users,email_user',
            'password' => 'bail|required'
        ], $messages);


        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "redirect_to" => null,
            "title" => "AUTHENTIFIEZ-VOUS",
            "message" => $validator->errors()->first()
        ]);

        
        $tempUser = User::where('email_user', $request->email)->get()->first();


        if ($tempUser && Hash::check($request->password, $tempUser->password)) 
        {
            if ($tempUser->status_user) {
                Auth::login($tempUser);
                $redirect = null;

                $session = Session::all();

                if ($tempUser->roles_user == "Client") {
                    if (!array_key_exists('details_url_previous', $session)) {
                        $redirect = route('utilisateur.tableaudebord');
                    } else {
                        $redirect = $session['details_url_previous'];
                    }
                } elseif ($tempUser->roles_user == "Hotel") {
                    $redirect = route('hotel.tableaudebord');
                } elseif ($tempUser->roles_user == "Admin") {
                    $redirect = route('admin.tableaudebord');
                }
                
                elseif ($tempUser->roles_user == "Superadmin") {
                    $redirect = route('superadmin.tableaudebord');
                }

                return response()->json([
                    "status" => true,
                    "reload" => false,
                    "redirect_to" => $redirect,
                    "title" => "AUTHENTIFIEZ-VOUS",
                    "message" => "Bienvenue " . $tempUser->prenoms_user . " " . $tempUser->nom_user . ", Connexion effectuée avec succes"
                ]);
            } else {

                Auth::logout();
                return response()->json([
                    "status" => false,
                    "reload" => true,
                    "redirect_to" => null,
                    "title" => "AUTHENTIFIEZ-VOUS",
                    "message" => "Votre compte a été desactivé. Contactez le service client."
                ]);
            }
        } 
         
        else {
            return response()->json([
                "status" => false,
                "reload" => true,
                "redirect_to" => null,
                "title" => "AUTHENTIFIEZ-VOUS",
                "message" => "Informations de connexion sont incorrectes."
            ]);
        }
        
    }


}
