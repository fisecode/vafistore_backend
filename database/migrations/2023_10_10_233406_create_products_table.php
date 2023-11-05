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
    Schema::create('products', function (Blueprint $table) {
      $table->id('id');
      $table->text('slug');
      $table->text('code');
      $table->text('title');
      $table->text('kategori');
      $table->integer('harga_modal');
      $table->integer('harga_jual');
      $table->integer('harga_reseller');
      $table->integer('harga_flash')->nullable();
      $table->string('image', 255);
      $table->text('currency');
      $table->text('type');
      $table->integer('flash_sale')->default(0);
      $table->integer('status');
      $table->integer('jenis');
      $table->tinyInteger('product_type');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('products');
  }
};
