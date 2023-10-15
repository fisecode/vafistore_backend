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
    Schema::create('services', function (Blueprint $table) {
      $table->id('cuid');
      $table->text('slug');
      $table->text('layanan');
      $table->text('cekID');
      $table->string('image');
      $table->integer('parent')->default(0);
      $table->text('deskripsi');
      $table->text('bantuan');
      $table->text('subtitle');
      $table->string('subimage');
      $table->integer('populer')->default(0);
      $table->integer('sort')->default(0);
      $table->date('created_date');
      $table->text('user');
      $table->integer('status')->default(1);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('services');
  }
};
