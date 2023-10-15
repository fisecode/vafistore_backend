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
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->text('user');
      $table->string('name');
      $table->string('email')->unique();
      $table->text('no_hp');
      $table->text('level');
      $table->string('pinTrx');
      $table->integer('reff');
      $table->integer('uplineID');
      $table->datetime('join_date');
      $table->datetime('last_login');
      $table->timestamp('email_verified_at')->nullable();
      $table->string('password');
      $table->rememberToken();
      $table->foreignId('current_team_id')->nullable();
      $table->string('profile_photo_path', 2048)->nullable();
      $table->integer('status')->default(0);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('users');
  }
};
