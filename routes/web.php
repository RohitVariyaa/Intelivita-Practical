<?php

use App\Http\Controllers\LeaderboardController;
use Illuminate\Support\Facades\Route;

// routes/web.php
Route::get('/', [LeaderboardController::class, 'index'])->name('leaderboard');
Route::get('/recalculate', [LeaderboardController::class, 'recalculate'])->name('recalculate');

