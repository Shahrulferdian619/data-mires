<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenerimaanBarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penerimaan_barang', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');

            $table->unsignedBigInteger('pesanan_pembelian_id');
            $table->foreign('pesanan_pembelian_id')
                ->references('id')
                ->on('pesanan_pembelian');

            $table->string('nomer_penerimaan_barang')->unique();
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('penerimaan_barang_rinci', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('penerimaan_barang_id');
            $table->foreign('penerimaan_barang_id')
                ->references('id')
                ->on('penerimaan_barang')
                ->onDelete('cascade');

            $table->unsignedBigInteger('item_id');
            $table->string('deskripsi_item')->nullable();
            $table->double('kuantitas');
            $table->string('catatan')->nullable();

            $table->timestamps();
        });

        Schema::create('penerimaan_barang_berkas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('penerimaan_barang_id');
            $table->foreign('penerimaan_barang_id')
                ->references('id')
                ->on('penerimaan_barang')
                ->onDelete('cascade');

            $table->string('nama_berkas');

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
        Schema::dropIfExists('penerimaan_barang_berkas');
        Schema::dropIfExists('penerimaan_barang_rinci');
        Schema::dropIfExists('penerimaan_barang');
    }
}
