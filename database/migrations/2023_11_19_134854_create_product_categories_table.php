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
    Schema::create('product_categories', function (Blueprint $table) {
      $table->id();
      $table->text('slug');
      $table->text('name')->unique();
      $table->text('cek_id')->nullable();
      $table->string('image')->nullable();
      $table->unsignedBigInteger('type')->default(0);
      $table->text('description')->nullable();
      $table->text('help_text')->nullable();
      $table->text('subtitle')->nullable();
      $table->string('subimage')->nullable();
      $table->integer('popular')->default(0);
      $table->integer('sort')->default(0);
      $table->unsignedBigInteger('user_id');
      $table->integer('status')->default(1);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('product_categories');
  }
};
