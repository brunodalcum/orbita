<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Operacao;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rota para buscar operações
Route::get('/operacoes', function () {
    return Operacao::orderBy('nome')->get();
});
