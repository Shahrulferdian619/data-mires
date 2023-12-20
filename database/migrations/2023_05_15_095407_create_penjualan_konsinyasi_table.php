<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenjualanKonsinyasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('second_mysql')->create('penjualan_konsinyasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pelanggan_id');
            $table->unsignedBigInteger('gudang_id');
            $table->unsignedBigInteger('akun_bank_id')->nullable();
            $table->unsignedBigInteger('akun_ppn_id')->nullable();
            $table->unsignedBigInteger('akun_diskon_id')->nullable();
            $table->unsignedBigInteger('sales_id');
            $table->unsignedBigInteger('created_by');

            $table->string('nomer_penjualan_konsinyasi');
            $table->date('tanggal');

            $table->string('keterangan')->nullable();

            $table->double('ppn')->default(0);
            $table->double('nilai_ppn')->default(0);

            $table->double('diskon_persen_global')->default(0);
            $table->double('diskon_nominal_global')->default(0);

            $table->double('total_sebelum_diskon')->default(0);
            $table->double('total_setelah_diskon')->default(0);
            $table->double('grandtotal')->default(0);

            $table->string('status_proses')->default(0)->comment('0=belum lunas, 1=sudah lunas');

            $table->timestamps();
        });

        Schema::connection('second_mysql')->create('penjualan_konsinyasi_rinci', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penjualan_konsinyasi_id');
            $table->foreign('penjualan_konsinyasi_id')->references('id')->on('penjualan_konsinyasi')->onDelete('cascade');
            
            $table->unsignedBigInteger('gudang_id');
            $table->unsignedBigInteger('produk_id');
            $table->double('kuantitas')->default(0);
            $table->double('diskon_persen')->default(0);
            $table->double('diskon_nominal')->default(0);
            $table->double('subtotal')->default(0);
            $table->double('catatan')->default(0);

            $table->timestamps();
        });

        Schema::connection('second_mysql')->create('penjualan_konsinyasi_berkas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penjualan_konsinyasi_id');
            $table->foreign('penjualan_konsinyasi_id')->references('id')->on('penjualan_konsinyasi')->onDelete('cascade');

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
        Schema::dropIfExists('penjualan_konsinyasi_rinci','penjualan_konsinyasi_berkas','penjualan_konsinyasi');
    }
}
