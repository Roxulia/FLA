<?php

use App\Http\Controllers\AdminContoller;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\leagueController;
use App\Http\Controllers\leaguePositionController;
use App\Http\Controllers\liveController;
use App\Http\Controllers\playerController;
use App\Http\Controllers\teamController;
use App\Http\Controllers\matchController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.admin:admin')->group(
    function()
    {
        Route::get('/me',[AdminContoller::class,'me']);
        Route::post('/admins',[AdminContoller::class,'createAdmin'])->middleware('check_role:Admin');
        Route::get('/test-live', [ApiController::class, 'testLiveAPI']);
        Route::post('/leagues', [leagueController::class, 'createLeague']);
        Route::put('/leagues/{id}', [leagueController::class, 'updateLeague']);
        Route::delete('/leagues/{id}', [leagueController::class, 'deleteLeague']);
        Route::post('/teams', [teamController::class, 'createTeam']);
        Route::put('/teams/{id}', [teamController::class, 'updateTeam']);
        Route::delete('/teams/{id}', [teamController::class, 'deleteTeam']);
        Route::post('/matches', [matchController::class, 'createMatch']);
        Route::put('/matches/{id}', [matchController::class, 'updateMatch']);
        Route::delete('/matches/{id}', [matchController::class, 'deleteMatch']);
        Route::post('/players', [playerController::class, 'createPlayer']);
        Route::put('/players/{id}', [playerController::class, 'updatePlayer']);
        Route::delete('/players/{id}', [playerController::class, 'deletePlayer']);
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
Route::get('/all-matches', [matchController::class, 'getAllMatches']);
Route::get('/matches/{id}', [matchController::class, 'getMatchByApiId']);
Route::get('/live-matches',[liveController::class,'getAllLives']);
