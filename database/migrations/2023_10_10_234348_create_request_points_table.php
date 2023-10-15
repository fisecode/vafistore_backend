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
    Schema::create('request_points', function (Blueprint $table) {
      $table->id('cuid');
      $table->integer('userID');
      $table->integer('rewardID');
      $table->integer('nominal');
      $table->string('image');
      $table->datetime('approve_date');
      $table->datetime('created_date');
      $table->integer('status');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('request_points');
  }
};
