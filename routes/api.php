<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LivreurController;
use App\Http\Controllers\PersonnelRestaurantController;
use App\Http\Controllers\RoleController;
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


});

// Route::middleware(['auth:api', 'role:utilisateur'])->group(function () {



// });

// Route::middleware(['auth:api', 'role:livreur'])->group(function () {



// });

// Route::middleware(['auth:api', 'role:client'])->group(function () {



// });

// Route::middleware(['auth:api', 'role:personnelrestaurant'])->group(function () {



// });