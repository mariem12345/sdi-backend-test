<?php

use App\Http\Controllers\Api\ArtistController;
use App\Http\Controllers\Api\TrackController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Artist Routes
Route::prefix('artists')->group(function () {
    Route::get('/', [ArtistController::class, 'index']);
    Route::get('/{id}', [ArtistController::class, 'show']);
    Route::get('/{id}/top-tracks', [ArtistController::class, 'topTracks']);

    // Local CRUD operations
    Route::post('/local', [ArtistController::class, 'storeLocal']);
    Route::delete('/local/{id}', [ArtistController::class, 'destroyLocal']);
});

// Track Routes
Route::prefix('tracks')->group(function () {
    Route::get('/', [TrackController::class, 'index']);
    Route::get('/{id}', [TrackController::class, 'show']);
    Route::get('/recommendations', [TrackController::class, 'recommendations']);

    // Local CRUD operations
    Route::post('/local', [TrackController::class, 'storeLocal']);
    Route::delete('/local/{id}', [TrackController::class, 'destroyLocal']);
});
