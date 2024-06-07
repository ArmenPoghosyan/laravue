<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
	EntryController,
	UserController
};

Route::get('/reset/{token}', [EntryController::class, 'index'])->name('password.reset');
Route::get('/email/update',	 [UserController::class, 'update_email'])->name('email.verify');

// * Public Routes * //
