<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('slides', function (Blueprint $table) {
      $table->id();
      $table->string('image');
      $table->text('description')->nullable();
      $table->integer('sort')->unique();
      $table->string('user_id');
      $table->integer('status');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('slides');
  }
};
