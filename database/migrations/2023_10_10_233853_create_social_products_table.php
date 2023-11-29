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
    Schema::create('social_products', function (Blueprint $table) {
      $table->id('id');
      $table->text('slug');
      $table->text('code');
      $table->text('item');
      $table->text('category');
      $table->text('description');
      $table->integer('min_buy');
      $table->integer('max_buy');
      $table->bigInteger('capital_price');
      $table->bigInteger('selling_price');
      $table->bigInteger('reseller_price');
      $table->string('image', 255)->nullable();
      $table->integer('type');
      $table->integer('status');
      $table->integer('provider');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('social_products');
  }
};
