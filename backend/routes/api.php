<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class,'login'])->name('login');

Route::post('/register', [AuthController::class,'register'])->name('register');

Route::post('/logout', [AuthController::class,'logout'])->name('logout');

Route::middleware('auth:sanctum')->prefix('tasks')->group(function () {
    Route::get('/', [TaskController::class, 'index']);
    Route::post('/', [TaskController::class, 'store']);
    Route::get('{id}', [TaskController::class, 'show']);
    Route::put('{id}', [TaskController::class, 'update']);
    Route::delete('{id}', [TaskController::class, 'destroy']);
    Route::post('{taskId}/comments', [TaskController::class, 'addComment']);
    Route::patch('{id}/status', [TaskController::class, 'toggleStatus']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
