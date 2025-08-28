<?php

use App\Http\Controllers\AdminContoller;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\leagueController;
use App\Http\Controllers\leaguePositionController;
use App\Http\Controllers\playerController;
use App\Http\Controllers\teamController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.admin:admin')->group(
    function()
    {
        Route::get('/me',[AdminContoller::class,'me']);
        Route::post('/admins',[AdminContoller::class,'createAdmin'])->middleware('check_role:Admin');
        Route::get('/fetch-live', [ApiController::class, 'testLiveAPI']);
        Route::post('/leagues', [leagueController::class, 'createLeague']);
        Route::put('/leagues/{id}', [leagueController::class, 'updateLeague']);
        Route::delete('/leagues/{id}', [leagueController::class, 'deleteLeague']);
    }
);

Route::get('/admin/login',[AdminContoller::class,'login']);
Route::get('/all-leagues', [leagueController::class, 'getAllLeagues']);
Route::get('/leagues/{id}', [leagueController::class, 'getLeagueByApiId']);
Route::get('/league-table/{league_id}/{season_id}', [leaguePositionController::class, 'getLeagueTable']);
Route::get('/team-position/{team_id}/{league_id}/{season_id}', [leaguePositionController::class, 'getTeamPosInLeague']);
Route::get('/all-teams', [teamController::class, 'getAllTeams']);
Route::get('/teams/{id}', [teamController::class, 'getTeamByApiId']);
Route::get('/all-players', [playerController::class, 'getAllPlayers']);
Route::get('/players/{id}', [playerController::class, 'getPlayerByApiId']);
