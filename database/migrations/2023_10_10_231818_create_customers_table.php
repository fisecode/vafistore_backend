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
    Schema::create('customers', function (Blueprint $table) {
      $table->id('cuid');
      $table->string('id_session', 100);
      $table->string('kd_transaksi', 25)->unique();
      $table->text('full_name');
      $table->text('email');
      $table->text('alamat');
      $table->text('provinsi');
      $table->text('city');
      $table->text('kecamatan');
      $table->text('kelurahan');
      $table->text('kode_pos');
      $table->text('no_hp');
      $table->integer('kode_unik');
      $table->integer('total');
      $table->datetime('tanggal');
      $table->string('no_resi', 100);
      $table->date('tgl_kirim');
      $table->text('metode');
      $table->integer('userID');
      $table->integer('status')->default(0);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('customers');
  }
};
