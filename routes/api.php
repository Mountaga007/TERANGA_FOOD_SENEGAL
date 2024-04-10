<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LivreurController;
use App\Http\Controllers\PersonnelRestaurantController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\Utilisteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class ,'me']);

});

// Route public
Route::post('inscription_liveur', [LivreurController::class, 'store']);
Route::post('inscription_user', [Utilisteur::class, 'store']);
Route::get('liste_article', [ArticleController::class, 'index']);
Route::get('detail_article/{id}', [ArticleController::class, 'show']);


// Toute personne authentifie

// Route::middleware(['auth:api'])->group(function () {



// });

Route::middleware(['auth:api', 'role:admin'])->group(function () {
     Route::post('create_role', [RoleController::class, 'store']);
     Route::get('liste_role', [RoleController::class, 'index']);
     Route::get('detail_role/{id}', [RoleController::class, 'show']);
     Route::post('update_role/{id}', [RoleController::class, 'update']);
     Route::delete('delete_role/{id}', [RoleController::class, 'destroy']);
     Route::post('inscription_personnel_restaurant', [PersonnelRestaurantController::class, 'store']);
     Route::post('updates_article/{id}', [ArticleController::class, 'update']);
     Route::delete('supprimer_articles/{id}', [ArticleController::class, 'destroy']);
     Route::get('listes_table', [TableController::class, 'index']);
     Route::get('details_table/{id}', [TableController::class, 'show']);
     Route::post('updates_table/{id}', [TableController::class, 'update']);
     Route::delete('supprimer_tables/{id}', [TableController::class, 'destroy']);


});

// Route::middleware(['auth:api', 'role:utilisateur'])->group(function () {



// });

// Route::middleware(['auth:api', 'role:livreur'])->group(function () {



// });


// Route::middleware(['auth:api', 'role:client'])->group(function () {


// });

 Route::middleware(['auth:api', 'role:personnel restaurant'])->group(function () {
    Route::post('/ajouter_article', [ArticleController::class, 'store']);
    Route::post('update_article/{id}', [ArticleController::class, 'update']);
    Route::delete('supprimer_article/{id}', [ArticleController::class, 'destroy']);
    Route::post('ajouter_table', [TableController::class, 'store']);
    Route::get('liste_table', [TableController::class, 'index']);
    Route::get('detail_table/{id}', [TableController::class, 'show']);
    Route::post('update_table/{id}', [TableController::class, 'update']);
    Route::delete('supprimer_table/{id}', [TableController::class, 'destroy']);
    
});

