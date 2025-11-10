<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TaskController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/tasks/{task}', [TaskController::class, 'show']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::post('/tasks/{task}', [TaskController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
