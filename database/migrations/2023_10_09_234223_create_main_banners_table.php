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
    Schema::create('main_banners', function (Blueprint $table) {
      $table->id('cuid'); // Primary Key
      $table->string('image', 255);
      $table->text('content');
      $table->text('status');
      $table->timestamps(); // Optional timestamps
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('main_banners');
  }
};
