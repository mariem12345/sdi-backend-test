<?php

use App\Http\Controllers\ArtistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
});

// Artist CRUD routes
Route::apiResource('artists', ArtistController::class);

// Additional artist routes
Route::get('/artists/popularity/range', [ArtistController::class, 'byPopularity']);

// Health check route
Route::get('/health', function () {
    return response()->json(['status' => 'OK', 'message' => 'API is running']);
});
