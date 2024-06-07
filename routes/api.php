<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
	LocalizationsController,
	MultimediaController,
	SettingsController,
	UserController,
	UsersController,
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

	//* Settings *//
	ROute::prefix('settings')->group(function () {
		Route::post('/',				[SettingsController::class, 'index']);
		Route::put('/',					[SettingsController::class, 'update']);
	});

	//* User *//
	Route::prefix('user')->group(function () {
		Route::get('/',					[UserController::class, 'user']);
		Route::put('/',					[UserController::class, 'update']);

		Route::prefix('password')->group(function () {
			Route::put('/',				[UserController::class, 'update_password']);
			Route::post('check',		[UserController::class, 'check_password']);
		});

		Route::prefix('email')->group(function () {
			Route::put('/',				[UserController::class, 'change_email']);
		});
	});

	//* Users *//
	Route::prefix('users')->group(function () {
		Route::post('/', 				[UsersController::class, 'index']);
		Route::post('create',			[UsersController::class, 'store']);

		Route::prefix('{TUser}')->group(function () {
			Route::get('/',				[UsersController::class, 'show']);
			Route::put('/',				[UsersController::class, 'update']);
			Route::put('/status',		[UsersController::class, 'update_status']);
			Route::delete('/',			[UsersController::class, 'destroy']);
		});
	});

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
