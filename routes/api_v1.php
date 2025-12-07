<?php

use App\Http\Controllers\API\V1\TicketsController;
use App\Http\Controllers\API\V1\AuthorsController;
use App\Http\Controllers\API\V1\AuthorTicketsController;

Route::apiResource('tickets', TicketsController::class)->middleware('auth:sanctum');
Route::apiResource('authors', AuthorsController::class)->middleware('auth:sanctum');
Route::apiResource('authors.tickets', AuthorTicketsController::class )->middleware('auth:sanctum');
