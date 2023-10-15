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
    Schema::create('posts', function (Blueprint $table) {
      $table->id('cuid');
      $table->text('slug');
      $table->text('title');
      $table->text('meta_desc');
      $table->text('keyword');
      $table->string('image', 255);
      $table->string('video', 200);
      $table->longText('content');
      $table->text('author');
      $table->text('kategori');
      $table->date('created_date');
      $table->date('last_update');
      $table->text('user');
      $table->integer('status');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('posts');
  }
};
