<?php 

use App\Http\Controllers\InterventionController;
use App\Http\Controllers\ReparationController;
use App\Http\Controllers\VoitureController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\ReparationInterventionController;
use Illuminate\Support\Facades\Route;


Route::prefix('backoffice')->group(function () {

    Route::apiResource('interventions', InterventionController::class);
    Route::apiResource('voitures', VoitureController::class);
    Route::apiResource('reparations', ReparationController::class)->except(['index']);
    Route::put('reparation-intervention/{reparationIntervention}', [ReparationInterventionController::class,'update']);
    Route::get('statistiques', [StatistiqueController::class,'index']);
});

Route::get('frontoffice/reparations', [ReparationController::class,'index']);
Route::get('frontoffice/reparation/{reparation}/interventions', [ReparationInterventionController::class,'index']);
