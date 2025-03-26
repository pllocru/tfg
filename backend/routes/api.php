<?php

use Illuminate\Support\Facades\Route;

Route::get('/vehiculos', function () {
    return response()->json([
        ['id' => 1, 'matricula' => '1234ABC', 'marca' => 'Peugeot', 'modelo' => '308'],
        ['id' => 2, 'matricula' => '5678XYZ', 'marca' => 'Ford', 'modelo' => 'Focus'],
    ]);
});

