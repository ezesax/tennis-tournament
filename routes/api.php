<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\TournamentController;

/*SIMULATION ROUTE*/
Route::post('simulation', [MainController::class, 'simulate']);

/*OBTENER TORNEOS*/
Route::post('tournaments', [TournamentController::class, 'getCompletedTournaments']);
