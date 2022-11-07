<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('/tasks')
        ->controller(TaskController::class)
        ->group(function () {
            Route::post('/', 'createTask');
            Route::put('/{task}', 'taskDone');
            Route::get('/', 'AllTasks');
            Route::patch('/{task}', 'updateTask');
            Route::delete('/{task}', 'deleteTask');
        });
});
