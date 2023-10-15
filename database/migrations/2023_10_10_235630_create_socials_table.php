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
    Schema::create('socials', function (Blueprint $table) {
      $table->id('cuid');
      $table->text('facebook');
      $table->text('twitter');
      $table->text('googleplus');
      $table->text('instagram');
      $table->text('linkedin');
      $table->text('youtube');
      $table->datetime('date');
      $table->text('user');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('socials');
  }
};
