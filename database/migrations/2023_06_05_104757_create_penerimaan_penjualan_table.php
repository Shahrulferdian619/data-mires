<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenerimaanPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('second_mysql')->create('penerimaan_penjualan', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('akun_bank_id');
            $table->string('nomer_bukti');
            $table->date('tanggal');
            $table->double('jumlah_pembayaran');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::connection('second_mysql')->create('penerimaan_penjualan_berkas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penerimaan_penjualan_id');
            $table->foreign('penerimaan_penjualan_id')->references('id')->on('penerimaan_penjualan')->onDelete('cascade');

            $table->string('nama_berkas');
            $table->timestamps();
        });

        Schema::connection('second_mysql')->create('penerimaan_penjualan_rinci', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penerimaan_penjualan_id');
            $table->foreign('penerimaan_penjualan_id')->references('id')->on('penerimaan_penjualan')->onDelete('cascade');

            $table->unsignedBigInteger('penjualan_invoice_id');
            $table->foreign('penjualan_invoice_id')->references('id')->on('penjualan_invoice');

            $table->double('bayar')->default(0);
            $table->double('nominal_pembayaran')->default(0);
            $table->timestamps();
        });

        Schema::connection('second_mysql')->create('potongan_penerimaan_penjualan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penerimaan_penjualan_rinci_id');
            $table->foreign('penerimaan_penjualan_rinci_id','fk_penerimaan_penjualan_rinci')->references('id')->on('penerimaan_penjualan_rinci')->onDelete('cascade');

            $table->unsignedBigInteger('akun_potongan_id');
            $table->double('potongan');
            $table->string('catatan')->nullable();
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
        Schema::dropIfExists('potongan_penerimaan_penjualan');
        Schema::dropIfExists('penerimaan_penjualan_berkas');
        Schema::dropIfExists('penerimaan_penjualan_rinci');
        Schema::dropIfExists('penerimaan_penjualan');
    }
}
