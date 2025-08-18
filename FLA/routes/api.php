<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\leagueController;
use App\Http\Controllers\playerController;
use App\Http\Controllers\teamController;
use Illuminate\Support\Facades\Route;

Route::get('/fetch-live', [ApiController::class, 'testLiveAPI']);
Route::get('/all-leagues', [leagueController::class, 'getAllLeagues']);
Route::get('/leagues/{id}', [leagueController::class, 'getLeagueByApiId']);
Route::get('/all-teams', [teamController::class, 'getAllTeams']);
Route::get('/teams/{id}', [teamController::class, 'getTeamByApiId']);
Route::get('/all-players', [playerController::class, 'getAllPlayers']);
Route::get('/players/{id}', [playerController::class, 'getPlayerByApiId']);
