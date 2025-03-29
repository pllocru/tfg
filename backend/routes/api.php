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


Route::post('/login', [AuthController::class, 'login']);

Route::get('/usuarios', [UserController::class, 'index']);
Route::post('/usuarios', [UserController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});


/* Route::post('/debug-login', function (Request $request) {
    $user = \App\Models\User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['ok' => false, 'reason' => 'Usuario no encontrado']);
    }

    if (!Hash::check($request->password, $user->password)) {
        return response()->json(['ok' => false, 'reason' => 'Contraseña incorrecta']);
    }

    return response()->json(['ok' => true, 'message' => 'Login correcto']);
}); */


