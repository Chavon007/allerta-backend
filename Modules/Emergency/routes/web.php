<?php

use Illuminate\Support\Facades\Route;
use Modules\Emergency\Http\Controllers\EmergencyController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('emergencies', EmergencyController::class)->names('emergency');
});
