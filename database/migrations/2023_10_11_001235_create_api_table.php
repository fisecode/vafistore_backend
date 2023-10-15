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
      $table->id('cuid');
      $table->text('provider');
      $table->text('api_key');
      $table->text('private_key');
      $table->text('merchant_code');
      $table->tinyInteger('jenis');
      $table->tinyInteger('status');
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
