<?php

use Illuminate\Support\Facades\Route;
use Modules\Emergency\Http\Controllers\EmergencyController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post("/emergency", [EmergencyController::class, "startEmergency"]);
    Route::get("/emergency/received", [EmergencyController::class, "getReceivedServiceController"]);
    Route::post("/emergency/{id}/location", [EmergencyController::class, "addLocation"]);
    Route::post("/emergency/{id}/end", [EmergencyController::class, "endEmergency"]);
    Route::post("/emergency/{id}/respond", [EmergencyController::class, "respondServiceController"]);
    Route::get("/emergency/{id}/history", [EmergencyController::class, "history"]); 
    Route::get("/emergency/mine", [EmergencyController::class, "getMineController"]);
    Route::get("/emergency/active", [EmergencyController::class, "active"]);
    Route::get("/emergency/{id}", [EmergencyController::class, "show"]);
});
