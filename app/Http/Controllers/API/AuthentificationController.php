<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class AuthentificationController extends Controller
{
    // fonction pour l'inscription d'un nouvel utilisateur
    public function registration(Request $request){
        // Validation des informations
        $testvalidation = Validator::make($request->all(),[
            'name' => 'required|string|max:60',
            'email' => 'unique:users,email|required|email|max:100',
            'password'=>'required|confirmed'
        ]);
        // test de validation
        if($testvalidation->fails()){
            // si la validation a échoué retourner une response avec les erreurs de validation
            return response()->json([
                'message'=>$testvalidation->messages(),
                'status_code'=>422
            ],422);
        }
        else{
            try{
                // création de l'utilisateur
                $user=User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password'=>Hash::make($request->password)
                ]);
                // création du token d'accès à l'api
                $token = $user->createToken('tockenaccess')->plainTextToken;
                // retourner une response de succès avec les informations de l'utilisateur
                //et son token d'accès
                return response()->json([
                    'Utilisateur'=>$user,
                    'Token'=>$token,
                    'message'=>"Inscription réussie",
                    'status_code'=>200,

               ],200);
            }
            catch(Exception $exception){
                return response()->json($exception);
            }


        }

    }
    public function login(Request $request){
        // Validation des informations
        $testvalidation = Validator::make($request->all(),[
            'email' => 'required|email',
            'password'=>'required'
        ]);

        // test de validation
        if($testvalidation->fails()){
            // si la validation a échoué retourner une response avec les erreurs de validation
            return response()->json([
                'message'=>$testvalidation->messages(),
                'status_code'=>422
            ],422);
        }
        else{
            //cette fonction utilise la méthode attempt de la façade Auth de Laravel
            // et retourne True si les identifiants sont valides
            if(auth()->attempt($request->only(['email','password']))){
                // récupération de l'utilisateur authentifié
                $user=auth()->user();
                // création d'un nouveau token d'accès
                $token = $user->createToken('tockenaccess')->plainTextToken;
                // retourner un response de succès avec un message
                return response()->json([
                    'Utilisateur'=>$user,
                    'Token'=>$token,
                    'message'=>"Connexion réussie",
                    'status_code'=>200,

               ],200);

            }else{
                // retourner une response de non réussite avec un message
                return response()->json([
                    'message'=>"Identifiants invalide",
                    'status_code'=>403
                ],403);
            }
        }
    }

    public function logout(){
        //suppression du token d'accès de la bdd
        auth()->user()->tokens()->delete();
        // retourner une response de réussite avec un message
        return response()->json([
            'status_code'=>200,
            'message'=>"Le token d'accès a été ditruit"
        ],200);
    }

}
