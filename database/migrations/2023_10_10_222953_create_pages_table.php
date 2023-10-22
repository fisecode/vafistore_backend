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
    Schema::create('pages', function (Blueprint $table) {
      $table->id();
      $table->text('slug');
      $table->text('page_name');
      $table->longText('content')->nullable();
      $table->string('image')->nullable();
      $table->string('video')->nullable();
      $table->unsignedBigInteger('user_id');
      $table->integer('status');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('pages');
  }
};
