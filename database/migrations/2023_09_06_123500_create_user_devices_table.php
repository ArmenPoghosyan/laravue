<?php

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
		Schema::create('user_devices', function (Blueprint $table) {
			$table->id();

			$table->foreignId('user_id')->constrained()
				->cascadeOnDelete()->cascadeOnUpdate()
				//
			;

			$table->string('device_id')->nullable();
			$table->string('device_name')->nullable();
			$table->string('device_type')->nullable();
			$table->string('push_token')->nullable();

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('user_devices');
	}
};
