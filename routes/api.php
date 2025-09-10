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

// API Routes para Calendar
Route::prefix('calendar')->group(function () {
    // Rotas autenticadas
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/events', [App\Http\Controllers\Api\CalendarController::class, 'events']);
        Route::post('/events', [App\Http\Controllers\Api\CalendarController::class, 'store']);
        Route::patch('/events/{id}', [App\Http\Controllers\Api\CalendarController::class, 'update']);
        Route::delete('/events/{id}', [App\Http\Controllers\Api\CalendarController::class, 'destroy']);
        Route::post('/freebusy', [App\Http\Controllers\Api\CalendarController::class, 'freebusy']);
    });

    // Rotas públicas para agendamento (implementar depois)
    // Route::get('/public/events', [App\Http\Controllers\Api\CalendarController::class, 'publicEvents']);
    // Route::post('/public/freebusy', [App\Http\Controllers\Api\CalendarController::class, 'publicFreebusy']);
    // Route::post('/public/booking', [App\Http\Controllers\Api\CalendarController::class, 'publicBooking']);
});
