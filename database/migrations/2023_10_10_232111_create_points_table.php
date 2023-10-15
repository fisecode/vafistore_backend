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
    Schema::create('points', function (Blueprint $table) {
      $table->id('cuid');
      $table->integer('userID');
      $table->integer('active');
      $table->integer('pending');
      $table->integer('payout');
      $table->datetime('created_date');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('points');
  }
};
