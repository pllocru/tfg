<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;


Route::get('/vehiculos', function () {
    return response()->json([
        ['id' => 1, 'matricula' => '1234ABC', 'marca' => 'Peugeot', 'modelo' => '308'],
        ['id' => 2, 'matricula' => '5678XYZ', 'marca' => 'Ford', 'modelo' => 'Focus'],
    ]);
});

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/usuarios', [UserController::class, 'index']);
Route::post('/usuarios', [UserController::class, 'store']);
Route::put('/usuarios/{id}', [UserController::class, 'update']);
Route::delete('/usuarios/{id}', [UserController::class, 'destroy']);


Route::put('/me', [UserController::class, 'update']);
Route::put('/me/password', [UserController::class, 'changePassword']);
Route::delete('/me', [UserController::class, 'destroy']);









