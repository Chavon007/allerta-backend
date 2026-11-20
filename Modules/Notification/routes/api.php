<?php

use Illuminate\Support\Facades\Route;
use Modules\Notification\Http\Controllers\NotificationController;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/notifications/device-token', [NotificationController::class, 'saveDeviceToken']);

    Route::delete('/notifications/device-token', [NotificationController::class, 'deleteDeviceToken']);

    Route::get('/notifications/device-token', [NotificationController::class, 'getTokenController']);

});