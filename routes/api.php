<?php

use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    Route::post('/search', [SearchController::class, 'search']);
    Route::post('/compare', [SearchController::class, 'compare']);
});