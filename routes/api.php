<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/getMe', [UserController::class, 'getMe']);
    Route::prefix('/tasks')
        ->controller(TaskController::class)
        ->group(function () {
            Route::post('/', 'createTask');
            Route::put('/{task}', 'taskDone');
            Route::get('/', 'AllTasks');
            Route::patch('/{task}', 'updateTask');
            Route::delete('/{task}', 'deleteTask');
        });

    Route::prefix('/categories')->group(function (){
        Route::post('/', [CategoryController::class, 'store']);
        Route::get('/', [CategoryController::class, 'index']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });
});
