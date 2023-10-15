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
    Schema::create('orders', function (Blueprint $table) {
      $table->increments('cuid');
      $table->string('id_session');
      $table->string('kd_transaksi');
      $table->integer('produkID');
      $table->integer('harga_modal');
      $table->integer('harga_jual');
      $table->integer('qty');
      $table->integer('harga');
      $table->integer('potongan');
      $table->integer('kode_unik');
      $table->integer('sub_total');
      $table->integer('total_profit');
      $table->text('voucher');
      $table->text('kategori');
      $table->text('title');
      $table->text('userID');
      $table->text('zoneID');
      $table->text('nickname');
      $table->text('trxID');
      $table->text('services');
      $table->text('status_order');
      $table->text('note');
      $table->text('full_name');
      $table->text('email');
      $table->text('no_hp');
      $table->text('metode');
      $table->datetime('created_date');
      $table->integer('providerID');
      $table->integer('jenis');
      $table->text('ipaddress');
      $table->integer('id_user');
      $table->integer('status')->default(0);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('orders');
  }
};
