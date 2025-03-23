<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AnnonceController;
use App\Http\Controllers\API\CandidatureController;
use App\Http\Controllers\API\StatistiqueController;
use App\Http\Controllers\API\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Routes d'authentification
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:sanctum');
    Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);
    Route::post('/password/reset', [AuthController::class, 'resetPassword']);
});

// Routes pour les annonces
Route::apiResource('annonces', AnnonceController::class);

// Routes pour les candidatures
Route::prefix('candidatures')->group(function () {
    Route::get('/', [CandidatureController::class, 'index']);
    Route::get('/mes-candidatures', [CandidatureController::class, 'getMesCandidatures']);
    Route::get('/annonce/{annonceId}', [CandidatureController::class, 'getCandidaturesByAnnonce']);
    Route::get('/{id}', [CandidatureController::class, 'show']);
    Route::post('/', [CandidatureController::class, 'store']);
    Route::delete('/{id}', [CandidatureController::class, 'destroy']);
    Route::put('/{id}/statut', [CandidatureController::class, 'updateStatut']);
});

// Routes pour les notifications
Route::post('/notifications/candidature/{id}', [CandidatureController::class, 'notifyCandidatureStatusChange']);

// Routes pour les utilisateurs
Route::prefix('utilisateurs')->group(function () {
    Route::get('/profil', [UserController::class, 'getProfil']);
    Route::put('/profil', [UserController::class, 'updateProfil']);
    Route::delete('/{id}', [UserController::class, 'deleteUser']);
});

// Routes pour les statistiques
Route::prefix('stats')->group(function () {
    Route::get('/recruteur', [StatistiqueController::class, 'getRecruteurStats']);
    Route::get('/globales', [StatistiqueController::class, 'getGlobalStats']);
});