<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up()
  {
    Schema::create('banks', function (Blueprint $table) {
      $table->id('cuid'); // Primary Key
      $table->string('image', 255);
      $table->text('akun');
      $table->text('pemilik');
      $table->text('no_rek');
      $table->integer('userID');
      $table->timestamps(); // Optional timestamps
    });
  }

  public function down()
  {
    Schema::dropIfExists('banks');
  }
};
