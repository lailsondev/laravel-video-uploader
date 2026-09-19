<?php

use App\Http\Controllers\Media\ContentController;
use App\Http\Controllers\Media\VideosController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';

Route::prefix('media')
    ->name('media.')
    ->middleware('auth')
    ->group(function () {
        Route::resource('contents', ContentController::class);

        Route::get('/contents/{content}/videos/upload', [VideosController::class, 'index'])
            ->name('contets.videos.upload');
    });
