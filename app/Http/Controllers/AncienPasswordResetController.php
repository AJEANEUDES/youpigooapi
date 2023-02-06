<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AncienPasswordResetController extends Controller
{


 
    public function send_reset_password_email(Request $request)
    {

      
            $messages = [

                "email_user.required" => "Votre adresse mail est requise",
                "email_user.email" => "Votre adresse mail est invalide",
                // "password.password" => "Le mot de passe est incorrecte",
                // "password.required" => "Le mot de passe est requis",
                // "password.min" => "Le mot de passe est trop court",
            ];

            $validator = Validator::make($request->all(), [

                "email_user" => "bail|required|email|max:50|",
                // "password" => "bail|required|min:8|password|max:50",

            ], $messages);
            
            if ($validator->fails()) return response()->json([
                "status" => false,
                "reload" => false,
                "title" => "REINITIALISATION DE MOT DE PASSE",
                "message" => $validator->errors()->first()
            ]);
            //checker l'email de l'utilisateur s'il existe ou pas

            $user = User::where('email_user', $request->email_user)->first();

            if (!$user) {
                return response()->json(
                    [
                        "Status" => false,
                        "Title" => "ERREUR",
                        "message" => "l'Email saisi n'existe pas.",

                    ],
                    404
                );
            }

            //Générer un token

            $token = Str::random(60);

            //Suavegarde de la donnée dans la table de Mot de passe

            PasswordReset::create([
                'email_user' => $request->email_user,
                'token' => $token,
                'created_at' => Carbon::now(),
            ]);

            dump("http://127.0.0.1.8000/api/auth/reset/" .$token);

            /* envoi de mail avec le lien de réinitialisation
            du mot de passe*/
            
            return response()->json(
                [
                    "Status" => true,
                    "redirect_to" => url(""),
                    "Title" => "ENVOI DE REINITIALISATION DU MOT DE PASSE",
                    "message" => "Le lien de réinitialisation du mot de passe vous a été envoyé par mail
                     . Veuillez vérifier votre adresse mail.",

                ],
                200
            );
        }
    }
