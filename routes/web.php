<?php

use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

// Define the search routes
Route::get('/', [SearchController::class, 'index'])->name('search.index');
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/search/results', [SearchController::class, 'search'])->name('search');
