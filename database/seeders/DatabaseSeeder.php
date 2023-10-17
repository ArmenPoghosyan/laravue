<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Localization;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		User::create([
			'first_name'	=> 'Admin',
			'last_name'		=> 'Admin',
			'email'			=> 'admin@app.com',
			'password'		=> 'password',
			'type'			=> User::TYPE_ADMIN,
			'language'		=> 'en',
		]);

		app()->useLangPath(resource_path('default_locales'));
		Localization::restore_from_files();

		app()->useLangPath(app()->storagePath('lang'));
		Localization::buildLocalizations();
	}
}
