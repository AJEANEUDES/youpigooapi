<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Mail\ForgetPasswordMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PrivateController extends Controller
{
    public function viewResetPassword()
    {
        return view('pages.reset_password');
    }

    public function resetPassword(Request $request)
    {
        $messages = [
            'email_user.required' => "Votre adresse mail est requis",
            'email_user.email' => "Votre adresse mail est invalide"
        ];

        $validator = Validator::make($request->all(), [
            'email_user' => "bail|required|email"
        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "REINITIALISATION DE MOT DE PASSE",
            "message" => $validator->errors()->first()
        ]);

        $user = User::where('email_user', $request->email_user)->first();

        if (!$user) {
            return response()->json([
                "status" => false,
                "reload" => false,
                "title" => "REINITIALISATION DE MOT DE PASSE",
                "message" => "Vous n'avez pas de compte, donc impossible d'effectuer cette action !"
            ]);
        } else {
            $reset_code = Str::random(200);
            $passwordReset = new PasswordReset();
            $passwordReset->user_id = $user->id;
            $passwordReset->reset_code = $reset_code;
            $process = $passwordReset->save();

            if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Reinitialisation de mot de passe de 'utilisateur " . $user->nom_user . " " . $user->prenom_user . " avec succes dans le système.");
            else saveSysActivityLog(SYS_LOG_ERROR, "Echec de reinitialisation de mot de passe de l'utilisateur " . $user->nom_user . " " . $user->prenom_user . " avec succes dans le système.");

            //Envoie du mail de reinitialisation de mot de passe 
            Mail::to($user->email_user)->send(new ForgetPasswordMail($user->nom_user, $reset_code));

            return response()->json([
                "status" => true,
                "reload" => true,
                "title" => "REINITIALISATION DE MOT DE PASSE",
                "message" => "Mr/Mlle " . $user->nom_user . " " . $user->prenoms_user . " Nous vous avons envoyé un lien de réinitialisation de mot de passe. Merci de consulter vos emails."
            ]);
        }
    }

    public function resetNewPassword($reset_code)
    {
        $password_reset_data = PasswordReset::where('reset_code', $reset_code)->first();
        if (!$password_reset_data || Carbon::now()->subMinutes(10) > $password_reset_data->created_at) {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => route('reinitialiser.mon-de-passe.get'),
                "title" => "REINITIALISATION DE MOT DE PASSE",
                "message" => "Lien de réinitialisation du mot de passe invalide ou lien expiré."
            ]);
            //return redirect()->route('dakoma.forgetpwd')->with(["message_error" => "Lien de réinitialisation du mot de passe invalide ou lien expiré"]);
        } else {
            return view('pages.new_reset_password', compact('reset_code'));
        }
    }

    public function getPasswordReset($reset_code, Request $request)
    {
        $password_reset_data = PasswordReset::where('reset_code', $reset_code)->first();
        if (!$password_reset_data || Carbon::now()->subMinutes(50) > $password_reset_data->created_at) {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => route('reinitialiser.mon-de-passe.get'),
                "title" => "REINITIALISATION DE MOT DE PASSE",
                "message" => "Lien de réinitialisation du mot de passe invalide ou lien expiré."
            ]);
            //return redirect()->route('dakoma.forgetpwd')->with(["message_error" => "Lien de réinitialisation du mot de passe invalide ou lien expiré"]);
        } else {
            $messages = [
                "email_user.required" => "Votre adresse mail est requis",
                "password.required" => "Le nouveau mot de passe est requis",
                "password.min" => "Le nouveau mot de passe est trop court",
                "password.max" => "Le nouveau mot de passe est trop long",
                "password_confirmation.same" => "La confirmation du mot de passe ne corresponde pas"
            ];

            $validator = Validator::make($request->all(), [
                "email_user" => "bail|required|email",
                "password" => "bail|required|min:8|max:100",
                "password_confirmation" => "required|same:password"
            ], $messages);

            if ($validator->fails()) return response()->json([
                "status" => false,
                "reload" => false,
                "title" => "REINITIALISATION DE MOT DE PASSE",
                "message" => $validator->errors()->first()
            ]);

            $user = User::find($password_reset_data->user_id);

            if ($user->email_user != $request->email_user) {
                return response()->json([
                    "status" => false,
                    "reload" => false,
                    "redirect_to" => route('reinitialiser.mon-de-passe.get'),
                    "title" => "REINITIALISATION DE MOT DE PASSE",
                    "message" => "Votre adresse mail est incorrecte."
                ]);
                //return redirect()->back()->with(["message_error" => "Votre adresse mail est incorrect"]);
            } else {
                $password_reset_data->delete();
                $user->update(['password' => Hash::make($request->password)]);

                return response()->json([
                    "status" => true,
                    "reload" => true,
                    "redirect_to" => route('login'),
                    "title" => "REINITIALISATION DE MOT DE PASSE",
                    "message" => "Votre mot de passe a ete reinitialise, veuillez vous connecter avec votre nouveau mot de passe."
                ]);
                //return redirect()->route('login')->with(["message_success" => "Votre mot de passe a ete reinitialise, veuillez vous connecter avec votre nouveau mot de passe."]);
            }
        }
    }
}
