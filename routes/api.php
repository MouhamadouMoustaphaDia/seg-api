<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('login', [UserController::class, 'login']);
Route::post('addUser', [UserController::class, 'addUser']);
 //Grâce à ce middleware on ne ppourra pas acceder aux routes sans le token
Route::group(['middleware' => 'auth.jwt'], function () {
//authentification

Route::put('updateUser/{id}', [UserController::class, 'updateUser']);
Route::put('updateEvenement/{id}', [UserController::class, 'updateEvenement']);

Route::delete('deleteUser/{id}', [UserController::class,'deleteUser']);
Route::delete('deleteEvenement/{id}', [UserController::class,'deleteEvenement']);

Route::post('addProfil', [UserController::class, 'addProfil']);

Route::post('addEvenement', [UserController::class, 'addEvenement']);

Route::get('getEvenement', [UserController::class, 'getEvenement']);
Route::get('getAllEvenementByUser/{user_id}', [UserController::class, 'getAllEvenementByUser']);
Route::get('getProfil', [UserController::class, 'getProfil']);

});
