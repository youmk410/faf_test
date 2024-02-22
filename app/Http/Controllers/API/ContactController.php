<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
class ContactController extends Controller
{
    /**
     *  Fonction pour afficher tous les contacts
     */

    public function index()
    {
        // try et catch pour gérer les exceptions coté serveur
        try{
            // récupération de tous les contacts
            $contacts = Contact::all();
            // test si y'a des enregistrements dans la bdd
            if($contacts->count()>0){
                // si oui retourner une response avec la liste des contacts
                return response()->json([
                    'contacts'=>$contacts,
                    'status_code'=>200
                ],200);
            }
            else{
                // si non retourner une response avec un message
                return response()->json([
                    'message'=>"Aucun contact trouvé",
                    'status_code'=>404
                ],404);
            }
        }catch(Exception $exception){
            return response()->json($exception);
        }

     }


    /**
     * Fonction pour créer un contact
     */
    public function store(Request $request)
    {
        // Validation des informations
        $testvalidation = Validator::make($request->all(),[
            'firstname' => 'required|max:60',
            'lastname' => 'required|max:60',
            'address' => 'required|max:150',
            'email' => 'required|email|max:100',
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
                // création du contact
                $contact=Contact::create([
                    'firstname' => $request->firstname,
                    'lastname' => $request->lastname,
                    'address' => $request->address,
                    'email' => $request->email,
                ]);
                // envoi d'une response de succès avec les informations du contact
                return response()->json([
                    'contact'=>$contact,
                    'message'=>"Le contact a été ajouté avec succès",
                    'status_code'=>200,
               ],200);
            }
            catch(Exception $exception){
                return response()->json($exception);
            }


        }
    }

    /**
     * Afficher les informations d'un contact
     */
    public function show($id)
    {
        // rechercher le contact avec son ID
        $contact=Contact::find($id);
        if(!$contact){
            // envoyer une response de non réussite avec un message
            return response()->json([
                'message'=>"Contact non trouvé",
                'status_code'=>404
            ],404);
        }else{
            // envoyer une response de réussite avec les informations du contact
            return response()->json([
                'contact'=>$contact,
                'status_code'=>200,
           ],200);
        }

    }



    /**
     * Mise à jour d'un contact.
     */
    public function update(Request $request, $id)
    {
        // rechercher le contact avec son ID
        $contact=Contact::find($id);
        if(!$contact){
            // envoyer une response de non réussite avec un message
            return response()->json([
                'message'=>"Contact non trouvé",
                'status_code'=>404
            ],404);
        }else{
            // Validation des informations
            $testvalidation = Validator::make($request->all(),[
                'firstname' => 'required|max:60',
                'lastname' => 'required|max:60',
                'address' => 'required|max:150',
                'email' => 'required|email|max:100',
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
                    // Mise à jour des informations du contact avec les nouvelles info
                    $contact->update([
                        'firstname' => $request->firstname,
                        'lastname' => $request->lastname,
                        'address' => $request->address,
                        'email' => $request->email,
                    ]);
                    // envoyer une response de réussite avec un message de succès et les informations du contact
                    return response()->json([
                        'contact'=>$contact,
                        'message'=>"Le contact a été mis à jour avec succès",
                        'status_code'=>200,
                   ],200);
                }
                catch(Exception $exception){
                    return response()->json($exception);
                }


            }
        }


    }

    /**
     * Fonction pour la suppression d'un contact
     */
    public function destroy($id)
    {
        // rechercher le contact avec son ID
        $contact=Contact::find($id);
        if($contact){
            try{
                // suppression du contact
                $contact->delete();
                // envoyer une response de réussite avec un message de succès et les informations du contact supprimé
                return response()->json([
                    'contact'=>$contact,
                    'message'=>"Le contact a été supprimé avec succès",
                    'status_code'=>200,
                ],200);
            }
            catch(Exception $exception){
                return response()->json($exception);
            }

        }else{
            // envoyer une response de non réussite avec un message
            return response()->json([
                'message'=>"Contact non trouvé",
                'status_code'=>404
            ],404);
        }
    }

}
