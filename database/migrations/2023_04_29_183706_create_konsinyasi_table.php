<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKonsinyasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('second_mysql')->create('konsinyasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pelanggan_id');
            $table->string('nama_pelanggan')->nullable();
            $table->text('alamat_pelanggan')->nullable();
            
            $table->string('nomer_konsinyasi')->unique();
            $table->date('tanggal_konsinyasi');

            $table->string('gudang_asal');
            $table->string('gudang_tujuan');
            $table->text('keterangan')->nullable();

            $table->double('grandtotal')->nullable()->default(0);

            $table->string('penerima')->nullable();
            $table->text('alamat_penerima')->nullable();

            $table->string('ekspedisi')->nullable()->default('Driver Mires');
            $table->string('resi')->nullable();

            $table->string('status_proses')->default(0)->comment('0=belum diproses');
            $table->timestamps();
        });

        Schema::connection('second_mysql')->create('konsinyasi_rinci', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('konsinyasi_id');
            $table->string('gudang_asal');
            $table->string('gudang_tujuan');

            $table->unsignedBigInteger('produk_id');
            $table->string('kode_produk');
            $table->string('nama_produk');
            $table->double('kuantitas');
            $table->double('harga');
            $table->double('subtotal')->nullable()->default(0);
            $table->text('catatan')->nullable();

            $table->string('status_proses')->default(0)->comment('0=belum diproses');
            $table->timestamps();
        });

        Schema::connection('second_mysql')->create('konsinyasi_berkas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('konsinyasi_id');
            $table->string('berkas1')->nullable();
            $table->string('berkas2')->nullable();
            $table->string('berkas3')->nullable();
            $table->string('berkas4')->nullable();
            $table->string('berkas5')->nullable();
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
        Schema::dropIfExists(['konsinyasi', 'konsinyasi_rinci', 'konsinyasi_berkas']);
    }
}
