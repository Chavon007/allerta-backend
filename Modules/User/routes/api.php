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
    Route::get("/me", [AuthController::class, "me"]);
    Route::put("/profile/update", [AuthController::class, "updateProfile"]);
    Route::put("/change_password", [AuthController::class, "changePassword"]);
});



Route::middleware('auth:sanctum')->group(function () {
     Route::get("/emergency-contacts", [EmergencyContactController::class, "fetchContacts"]);
    Route::post('/emergency-contacts', [EmergencyContactController::class, 'addContact']);
    // search and remove routes here too.

    Route::delete("/emergency-contacts/{contactUserId}", [EmergencyContactController::class, "removeContact"]);
});