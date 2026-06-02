<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MenuController;

Route::middleware('throttle:60,1')->group(function () {
    Route::get('/menus', [MenuController::class, 'index'])->name('api.menus.index');
});
