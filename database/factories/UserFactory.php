<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'first_name'				=> fake()->firstName(),
			'last_name'					=> fake()->lastName(),
			'type'						=> User::TYPE_USER,
			'email'						=> fake()->email(),
			'phone'						=> fake()->phoneNumber(),
			'avatar'					=> '',
			'birth_date'				=> fake()->dateTimeBetween('-20 years', '-10 years'),
			'password'					=> 'password',
			'language'					=> 'en',
		];
	}
}
