<?php

use App\Http\Controllers\AmazonListingController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Create resource group for Amazon listings for logged in users
Route::resource('listings', AmazonListingController::class)
    ->middleware(['auth'])
    ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy', 'scrape']);

require __DIR__.'/auth.php';
