<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePesananPembelianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('second_mysql')->create('pesanan_pembelian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permintaan_pembelian_id');
            $table->foreign('permintaan_pembelian_id', 'fk_permintaan_pembelian')
                ->references('id')
                ->on('permintaan_pembelian')
                ->onDelete('cascade');

            $table->unsignedBigInteger('supplier_id');
            $table->foreign('supplier_id', 'fk_supplier')->references('id')->on('supplier');

            $table->string('status_proses')->default(0)->comment('0=belum diproses,1=sudah diproses');
            $table->unsignedBigInteger('created_by');

            $table->unsignedBigInteger('direktur_id');
            $table->unsignedBigInteger('komisaris_id');

            $table->string('approve_direktur', 1)->default(0);
            $table->string('approve_komisaris', 1)->default(0);

            $table->string('catatan_direktur')->nullable();
            $table->string('catatan_komisaris')->nullable();

            $table->string('nomer_pesanan_pembelian')->unique();
            $table->date('tanggal');
            $table->text('keterangan')->nullable();

            $table->double('diskon_persen_global')->default(0)->nullable();
            $table->double('diskon_nominal_global')->default(0)->nullable();

            $table->string('ppn')->default(0)->comment('0=non-ppn, 1=ppn, 2=include-ppn');
            $table->double('nilai_ppn')->default(0)->nullable();

            $table->double('biaya_kirim')->default(0);

            $table->double('total');
            $table->double('total_setelah_diskon');
            $table->double('grandtotal');

            $table->timestamps();
        });

        Schema::connection('second_mysql')->create('pesanan_pembelian_rinci', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pesanan_pembelian_id');
            $table->foreign('pesanan_pembelian_id')
                ->references('id')
                ->on('pesanan_pembelian')
                ->onDelete('cascade');

            $table->unsignedBigInteger('item_id');
            $table->string('deskripsi_item')->nullable();
            $table->double('kuantitas');
            $table->double('kuantitas_diterima')->default(0);
            $table->double('harga')->default(0);
            $table->double('diskon_persen')->default(0);
            $table->double('diskon_nominal')->default(0);
            $table->double('subtotal')->default(0);
            $table->string('catatan')->nullable();
            $table->date('tanggal_diminta')->nullable();
            $table->timestamps();
        });

        Schema::connection('second_mysql')->create('pesanan_pembelian_berkas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pesanan_pembelian_id');
            $table->foreign('pesanan_pembelian_id')
                ->references('id')
                ->on('pesanan_pembelian')
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
        Schema::dropIfExists('pesanan_pembelian_berkas');
        Schema::dropIfExists('pesanan_pembelian_rinci');
        Schema::dropIfExists('pesanan_pembelian');
    }
}
