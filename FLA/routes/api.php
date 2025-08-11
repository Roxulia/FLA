<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('/test-leagues', [ApiController::class, 'testLeagueAPI']);
