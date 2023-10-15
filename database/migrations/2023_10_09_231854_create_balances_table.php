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
    Schema::create('balances', function (Blueprint $table) {
      $table->id('cuid'); // Primary Key
      $table->integer('userID');
      $table->integer('active');
      $table->integer('pending');
      $table->integer('payout');
      $table->datetime('created_date');
      $table->timestamps(); // Optional timestamps
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('balances');
  }
};
