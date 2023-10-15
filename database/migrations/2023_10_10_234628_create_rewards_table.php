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
    Schema::create('rewards', function (Blueprint $table) {
      $table->id('cuid');
      $table->text('title');
      $table->text('nominal');
      $table->integer('min_order');
      $table->smallInteger('kuota');
      $table->tinyInteger('satuan');
      $table->tinyInteger('jenis');
      $table->tinyInteger('status')->default(1);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('rewards');
  }
};
