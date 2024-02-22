<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\AuthentificationController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// route pour inscription d'un utilisateur
Route::post('registration',[AuthentificationController::class, 'registration']);
// route pour inscription d'un utilisateur afin d'avoir un token d'accès
Route::post('login',[AuthentificationController::class, 'login']);



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['auth:sanctum'])->group(function(){
    // route pour la distruction des tokens d'accès
    Route::post('logout',[AuthentificationController::class, 'logout']);
    // route qui nous permet d'afficher tous les contacts
    Route::get('contacts',[ContactController::class,'index']);
    // route qui nous permet de créer un contact
    Route::post('contacts',[ContactController::class,'store']);
    // route qui nous permet d'afficher un contact
    Route::get('contacts/{contact}',[ContactController::class,'show']);
    // route qui nous permet de mettre à jour un contact
    Route::put('contacts/{contact}',[ContactController::class,'update']);
    // route qui nous permet de supprimer un contact
    Route::delete('contacts/{contact}',[ContactController::class,'destroy']);
    // nous pouvons remplacer toutes ces routes avec la route ci-dessous
    // Route::apiResource("contacts", ContactController::class);
});

