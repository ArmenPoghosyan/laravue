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
		Schema::create('localizations', function (Blueprint $table) {
			$table->id();

			$table->foreignId('parent_id')->nullable()
				->constrained('localizations')
				->cascadeOnDelete()->cascadeOnUpdate()
				//
			;

			$table->enum('type', ['node', 'value'])->default('value');
			$table->string('label')->nullable();
			$table->string('key');
			$table->string('language')->nullable();
			$table->text('value')->nullable();

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('localizations');
	}
};
