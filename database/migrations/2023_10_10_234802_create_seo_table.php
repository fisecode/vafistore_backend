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
    Schema::create('seo', function (Blueprint $table) {
      $table->id('cuid');
      $table->string('image')->default('logo.png');
      $table->text('instansi');
      $table->text('keyword');
      $table->text('deskripsi');
      $table->integer('template');
      $table->tinyInteger('warna');
      $table->tinyInteger('footer');
      $table->integer('upgrade');
      $table->text('urlweb');
      $table->text('user');
      $table->datetime('date');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('seo');
  }
};
