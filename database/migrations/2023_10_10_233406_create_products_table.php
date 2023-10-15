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
      $table->id('cuid');
      $table->text('slug');
      $table->text('code');
      $table->text('title');
      $table->text('kategori');
      $table->integer('harga_modal');
      $table->integer('harga_jual');
      $table->integer('harga_reseller');
      $table->string('image', 255);
      $table->text('currency');
      $table->text('type');
      $table->integer('status');
      $table->date('created_date');
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
