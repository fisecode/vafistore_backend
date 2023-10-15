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
    Schema::create('transactions', function (Blueprint $table) {
      $table->id('cuid');
      $table->string('kd_transaksi', 16);
      $table->datetime('date');
      $table->text('transaksi');
      $table->integer('total');
      $table->integer('saldo');
      $table->text('note');
      $table->integer('providerID');
      $table->text('jenis');
      $table->text('metode');
      $table->integer('userID');
      $table->integer('status')->default(0);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('transactions');
  }
};
