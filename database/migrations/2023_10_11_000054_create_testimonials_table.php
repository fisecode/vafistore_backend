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
    Schema::create('testimonials', function (Blueprint $table) {
      $table->id('cuid');
      $table->text('kd_transaksi');
      $table->integer('produkID');
      $table->text('full_name');
      $table->mediumText('content');
      $table->dateTime('date');
      $table->integer('status')->default(0);
      $table->integer('userID');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('testimonials');
  }
};
