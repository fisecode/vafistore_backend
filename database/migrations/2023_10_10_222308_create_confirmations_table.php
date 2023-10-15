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
    Schema::create('confirmations', function (Blueprint $table) {
      $table->increments('cuid');
      $table->integer('userID');
      $table->string('paymentID', 25);
      $table->string('kd_transaksi', 25);
      $table->string('image', 255);
      $table->datetime('tanggal');
      $table->text('bank_tujuan');
      $table->integer('total');
      $table->string('metode', 25)->default('transfer');
      $table->integer('status')->default(0);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('confirmations');
  }
};
