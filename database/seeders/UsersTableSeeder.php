<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		User::factory()->afterCreating(function (User $user) {
			//
		})->create([
			'first_name'    => 'Admin',
			'last_name'     => 'Admin',
			'email'         => 'admin@app.com',
			'password'      => 'password',
			'type'          => User::TYPE_ADMIN,
			'language'      => 'en',
		]);
	}
}
