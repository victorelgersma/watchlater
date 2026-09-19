<?php

use App\Http\Controllers\WatchItemController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('watch-items.index')
        : redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/watch', [WatchItemController::class, 'index'])->name('watch-items.index');
    Route::post('/watch', [WatchItemController::class, 'store'])->name('watch-items.store');
    Route::delete('/watch/{watchItem}', [WatchItemController::class, 'destroy'])->name('watch-items.destroy');
});

require __DIR__.'/auth.php';
