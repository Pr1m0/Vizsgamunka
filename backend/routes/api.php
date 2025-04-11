<?php
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChildController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\StatisticsControler;
use App\Http\Middleware\AdminMiddleware;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', AdminMiddleware::class])->group(function () {

    Route::get('/users', [AdminController::class, 'index']);
    Route::delete('/users/{id}', [AdminController::class, 'destroy']);
    Route::post('/users/{id}/promote-admin', [AdminController::class, 'promoteToAdmin']);

    Route::get('/admin/children', [AdminController::class, 'listChildren']);
    Route::delete('/admin/children/{id}', [AdminController::class, 'deleteChild']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/children', [ChildController::class, 'child_query']);
    Route::post('/children', [ChildController::class, 'add_child']);
    Route::put('/children/{id}', [ChildController::class, 'update']);
    Route::delete('/children/{id}', [ChildController::class, 'destroy']);
    Route::post('/children/{id}/games', [ChildController::class, 'assignGame']);
    Route::get('/children/{id}/games', [ChildController::class, 'getGamesForChild']);
    Route::delete('/children/{childId}/games/{gameId}', [ChildController::class, 'removeGame']);

    Route::get('/games', [GameController::class, 'game_query']);
    Route::post('/games', [GameController::class, 'add_game']);
    Route::put('/games/{id}', [GameController::class, 'update']);
    Route::delete('/games/{id}', [GameController::class, 'destroy']);

    Route::get('/statistics', [StatisticsControler::class, 'get_statistics']);
    Route::post('/statistics', [StatisticsControler::class, 'save_statistics']);
    Route::get('/user/statistics', [StatisticsControler::class, 'userStatistics']);
    Route::get('/parent/statistics', [StatisticsControler::class, 'parentStatistics']);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
