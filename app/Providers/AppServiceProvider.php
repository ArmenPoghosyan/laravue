<?php

namespace App\Providers;

use App\Helpers\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
		if ($this->app->environment('local')) {
			$this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
			$this->app->register(TelescopeServiceProvider::class);
		}

		$this->app->alias(Paginator::class, \Illuminate\Pagination\LengthAwarePaginator::class);
		$this->app->alias(Paginator::class, \Illuminate\Contracts\Pagination\LengthAwarePaginator::class);
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		//? Changed languages path into the storage to not loose the translations files when updating the app
		app()->useLangPath(app()->storagePath('lang'));
	}
}
