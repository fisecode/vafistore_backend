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
    Schema::create('product_types', function (Blueprint $table) {
      $table->id(); // Primary Key
      $table->text('name')->unique();
      $table->string('image', 255);
      $table->integer('sort');
      $table->integer('status')->default(1);
      $table->timestamps(); // Optional timestamps
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('product_types');
  }
};
