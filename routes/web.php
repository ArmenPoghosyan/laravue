<?php

use App\Http\Controllers\EntryController;
use Illuminate\Support\Facades\Route;

Route::get('/login',			[EntryController::class, 'index'])->name('login');
Route::get('/reset/{token}',	[EntryController::class, 'index'])->name('password.reset');
// * Public Routes * //
