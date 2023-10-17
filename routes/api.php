<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
	LocalizationsController,
	MultimediaController,
	WebAuthController,
};

// * App *//
Route::prefix('app')->group(function () {
	Route::get('localizations', 		[LocalizationsController::class, 'app']);
	Route::post('set_locale', 			[LocalizationsController::class, 'set_locale']);
});

// * Auth *//
Route::prefix('auth')->group(function () {
	Route::post('init',					[WebAuthController::class, 'init']);
	Route::post('login',				[WebAuthController::class, 'login']);
	Route::post('forgot',				[WebAuthController::class, 'forgot']);
	Route::post('reset',				[WebAuthController::class, 'reset']);
});

// * Authenticated *//
Route::middleware('auth:sanctum')->group(function () {
	Route::post('auth/logout',			[WebAuthController::class, 'logout']);

	// * Multimedia *//
	Route::prefix('multimedia')->group(function () {
		Route::post('/',				[MultimediaController::class, 'store']);
		Route::delete('{multimedia}',	[MultimediaController::class, 'destroy']);
	});

	// * Localizations *//
	Route::prefix('localizations')->group(function () {
		Route::get('/',					[LocalizationsController::class, 'index']);
		Route::post('/',				[LocalizationsController::class, 'store']);
		Route::post('sync',				[LocalizationsController::class, 'sync']);
		Route::get('{localization}',	[LocalizationsController::class, 'show']);
		Route::put('{localization}',	[LocalizationsController::class, 'update']);
		Route::delete('{localization}',	[LocalizationsController::class, 'destroy']);
	});
});
