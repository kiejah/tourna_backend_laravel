<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\TournamentsController;
use App\Http\Controllers\Api\GamesTableStandingController;


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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
//Route::group(['prefix'=>'v1','middleware'=>['auth:sanctum']],function () {
Route::group(['prefix'=>'v1'],function () {
    Route::apiResource('skills',SkillController::class);
    Route::apiResource('players',PlayerController::class);
    Route::apiResource('games',GameController::class);
    Route::apiResource('standings',GamesTableStandingController::class);
    Route::apiResource('tournaments',TournamentsController::class);
    Route::get('tournament_games/{id}', [GameController::class, 'tournamentGames']);
    Route::get('tournament_standings/{id}', [GamesTableStandingController::class, 'tournamentStandings']);
    Route::get('player-history/{id}/{t_id}', [GameController::class, 'playerGames']);
    
});
