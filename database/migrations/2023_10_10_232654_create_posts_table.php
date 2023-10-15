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
      $table->id('id');
      $table->unsignedBigInteger('user_id');
      $table->text('slug');
      $table->text('title');
      $table->text('meta_desc')->nullable();
      $table->text('keyword')->nullable();
      $table->string('image', 255);
      $table->string('video', 200)->nullable();
      $table->longText('content')->nullable();
      $table->text('kategori')->nullable();
      $table->text('tags')->nullable();
      $table->date('created_date');
      $table->date('last_update');
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
