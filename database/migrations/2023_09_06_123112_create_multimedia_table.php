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
		Schema::create('multimedia', function (Blueprint $table) {
			$table->id();
			$table->nullableMorphs('parentable');
			$table->string('path');
			$table->string('type')->nullable();
			$table->string('mime')->nullable();
			$table->integer('size')->nullable();
			$table->integer('order')->default(0);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('multimedia');
	}
};
