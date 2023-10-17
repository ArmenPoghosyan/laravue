<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('users', function (Blueprint $table) {
			$table->id();

			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('type')->default(User::TYPE_USER);
			$table->string('email')->unique();
			$table->string('phone')->nullable();
			$table->string('avatar')->nullable();
			$table->date('birth_date')->nullable();
			$table->string('language');
			$table->string('password')->nullable();

			$table->rememberToken();
			$table->softDeletes();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('users');
	}
};
