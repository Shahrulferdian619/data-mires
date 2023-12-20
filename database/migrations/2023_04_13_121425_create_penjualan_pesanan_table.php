<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenjualanPesananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('second_mysql')->create('penjualan_pesanan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pelanggan_id');
            $table->unsignedBigInteger('sales_id')->nullable();
            $table->unsignedBigInteger('created_by');
            
            $table->string('nomer_pesanan_penjualan')->unique();
            $table->date('tanggal');
            $table->text('keterangan')->nullable();

            $table->string('jenis_penjualan')->nullable();
            $table->string('ppn')->nullable()->default(0);
            $table->string('nomer_pesanan')->nullable();
            $table->string('resi')->nullable();
            $table->string('ekspedisi')->nullable();
            $table->string('penerima')->nullable();
            $table->text('alamat_penerima')->nullable();

            $table->double('diskon_persen')->nullable()->default(0);
            $table->double('diskon_global')->nullable()->default(0);

            $table->string('status_proses',1)->default(0);

            $table->double('grandtotal')->nullable()->default(0);
            $table->double('grandtotal_setelah_diskon')->nullable()->default(0);

            $table->timestamps();
        });

        Schema::connection('second_mysql')->create('penjualan_pesanan_rinci', function (Blueprint $table) {
            $table->id();
            // relasi ke tabel barang
            $table->unsignedBigInteger('produk_id');

            // relasi ke tabel pesanan_penjualan
            $table->unsignedBigInteger('penjualan_pesanan_id');
            $table->foreign('penjualan_pesanan_id')
                ->references('id')
                ->on('penjualan_pesanan')
                ->onDelete('cascade');

            $table->double('kuantitas');
            $table->double('harga_produk');
            $table->double('diskon_persen')->nullable()->default(0);
            $table->double('diskon_nominal')->nullable()->default(0);
            $table->double('potongan_admin')->nullable()->default(0);
            $table->double('cashback')->nullable()->default(0);
            $table->double('subtotal')->nullable()->default(0);
            $table->string('catatan')->nullable()->default(0);
            $table->string('status')->default(0)->comment('0=belum diproses, 1=sudah diproses');

            $table->timestamps();
        });

        Schema::connection('second_mysql')->create('penjualan_pesanan_berkas', function (Blueprint $table) {
            $table->id();
            // relasi ke tabel pesanan_penjualan
            $table->unsignedBigInteger('penjualan_pesanan_id');
            $table->foreign('penjualan_pesanan_id')
                ->references('id')
                ->on('penjualan_pesanan')
                ->onDelete('cascade');

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
        Schema::dropIfExists(['penjualan_pesanan', 'penjualan_pesanan_rinci', 'penjualan_pesanan_berkas']);
    }
}
