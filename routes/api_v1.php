<?php

use App\Http\Controllers\API\V1\TicketsController;
use App\Http\Controllers\API\V1\AuthorsController;
use App\Http\Controllers\API\V1\AuthorTicketsController;


Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('tickets', TicketsController::class)->except(['update']);

    Route::put('tickets/{ticket}', [TicketsController::class, 'replace'])
        ->name('tickets.replace');

    Route::patch('tickets/{ticket}', [TicketsController::class, 'update'])
        ->name('tickets.update');

    Route::apiResource('authors', AuthorsController::class);

    Route::apiResource('authors.tickets', AuthorTicketsController::class )
        ->except(['update']);



    Route::put('authors/{author}/tickets/{ticket}', [AuthorTicketsController::class, 'replace'])
        ->name('authors.tickets.replace');

    Route::patch('authors/{author}/tickets/{ticket}', [AuthorTicketsController::class, 'update'])
        ->name('authors.tickets.update');
});

