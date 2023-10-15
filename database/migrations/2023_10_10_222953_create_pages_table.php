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
      $table->increments('cuid');
      $table->text('slug');
      $table->text('nama_page');
      $table->longText('content');
      $table->string('image');
      $table->string('video');
      $table->date('created_date');
      $table->date('last_update');
      $table->text('user');
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
