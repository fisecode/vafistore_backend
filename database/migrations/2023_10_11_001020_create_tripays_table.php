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
    Schema::create('tripays', function (Blueprint $table) {
      $table->id('cuid');
      $table->integer('userID');
      $table->text('reference');
      $table->text('merchant_ref');
      $table->text('payment_method');
      $table->text('payment_name');
      $table->text('customer_email');
      $table->text('customer_phone');
      $table->integer('amount');
      $table->integer('fee');
      $table->integer('amount_total');
      $table->text('pay_code');
      $table->text('checkout_url');
      $table->enum('status', ['PAID', 'UNPAID', 'EXPIRED']);
      $table->datetime('paid_time');
      $table->datetime('expired_time');
      $table->integer('providerID');
      $table->integer('jenis_transaksi');
      $table->datetime('created_date');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('tripays');
  }
};
