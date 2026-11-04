<?php

use Illuminate\Support\Facades\Route;
use Modules\Emergency\Http\Controllers\EmergencyController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('emergencies', EmergencyController::class)->names('emergency');
});
