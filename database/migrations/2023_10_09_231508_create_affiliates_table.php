<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up()
  {
    Schema::create('affiliates', function (Blueprint $table) {
      $table->id('cuid'); // Primary Key
      $table->text('ip');
      $table->date('date');
      $table->text('user');
      $table->text('trxID');
      $table->timestamps(); // Optional timestamps
    });
  }

  public function down()
  {
    Schema::dropIfExists('affiliates');
  }
};
