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
    Schema::create('api', function (Blueprint $table) {
      $table->id('id');
      $table->text('provider')->unique();
      $table->text('api_key')->nullable();
      $table->text('private_key')->nullable();
      $table->text('merchant_code')->nullable();
      $table->tinyInteger('jenis')->nullable();
      $table->tinyInteger('status')->default(1);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('api');
  }
};
