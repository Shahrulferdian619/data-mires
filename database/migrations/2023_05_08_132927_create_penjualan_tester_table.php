<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenjualanTesterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('second_mysql')->create('penjualan_tester', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pelanggan_id');
            $table->unsignedBigInteger('sales_id');
            $table->unsignedBigInteger('gudang_id');
            $table->unsignedBigInteger('created_by');

            $table->string('nomer_permintaan_tester')->unique();
            $table->date('tanggal');
            $table->text('keterangan')->nullable();

            $table->string('nomer_pesanan')->nullable();
            $table->string('ekspedisi')->nullable();
            $table->string('resi')->nullable();
            $table->string('penerima')->nullable();
            $table->text('alamat_penerima')->nullable();

            $table->string('status_proses')->default(0);

            $table->timestamps();
        });

        Schema::connection('second_mysql')->create('penjualan_tester_rinci', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penjualan_tester_id');
            $table->unsignedBigInteger('gudang_id');
            $table->unsignedBigInteger('created_by');

            $table->unsignedBigInteger('produk_id');
            $table->double('kuantitas');
            $table->string('catatan')->nullable();

            $table->string('status_proses')->default(0);

            $table->timestamps();
        });

        Schema::connection('second_mysql')->create('penjualan_tester_berkas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penjualan_tester_id');
            $table->string('berkas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penjualan_tester');
    }
}
