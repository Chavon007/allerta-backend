<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserController;
use Modules\User\Http\Controllers\AuthController;
use Modules\User\Http\Controllers\EmergencyContactController;


Route::prefix('auth')->group(function () {
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['auth:sanctum'])->prefix("auth")->group(function () {
    Route::apiResource('users', UserController::class)->names('user');
    Route::get("/me", [AuthController::class, "me"]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/emergency-contacts', [EmergencyContactController::class, 'addContact']);
    // search and remove routes here too.
});