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
    Schema::create('markups', function (Blueprint $table) {
      $table->id('id'); // Primary Key
      $table->integer('persen_sell');
      $table->integer('persen_res');
      $table->integer('persen_flash')->default(0);
      $table->integer('com_ref');
      $table->integer('satuan');
      $table->timestamps(); // Optional timestamps
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('markups');
  }
};
