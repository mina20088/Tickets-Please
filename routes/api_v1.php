<?php

use App\Http\Controllers\API\V1\TicketsController;
use App\Http\Controllers\API\V1\UsersController;

Route::apiResource('/tickets', TicketsController::class)->middleware('auth:sanctum');
Route::apiResource('/users', UsersController::class)->middleware('auth:sanctum');
