<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenjualanInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('second_mysql')->create('penjualan_invoice', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penjualan_pesanan_id')->comment('jika transaksi pesanan penjualan dihapus, maka transaksi invoice juga terhapus'); // relasi ke tabel pesanan penjualan
            $table->foreign('penjualan_pesanan_id')->references('id')->on('penjualan_pesanan')->onDelete('cascade');
            
            $table->unsignedBigInteger('akun_bank_id')->nullable();
            $table->unsignedBigInteger('akun_ppn_id')->nullable();
            $table->unsignedBigInteger('akun_biayakirim_id')->nullable();
            $table->unsignedBigInteger('akun_diskon_id')->nullable();
            $table->unsignedBigInteger('pelanggan_id');
            $table->unsignedBigInteger('sales_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('gudang_id')->nullable();

            $table->string('nomer_invoice_penjualan')->unique();
            $table->date('tanggal');
            $table->string('keterangan')->nullable();
            $table->string('jenis_penjualan')->nullable();
            $table->string('ppn')->nullable()->default(0);
            $table->double('nilai_ppn')->nullable()->default(0);
            $table->string('nomer_pesanan')->nullable();
            $table->string('resi')->nullable();
            $table->string('ekspedisi')->nullable();
            $table->string('penerima')->nullable();
            $table->text('alamat_penerima')->nullable();
            $table->double('diskon_persen')->nullable()->default(0);
            $table->double('diskon_global')->nullable()->default(0);
            $table->double('biaya_kirim')->nullable()->default(0);
            $table->string('status_proses')->default(0);
            $table->double('grandtotal')->nullable()->default(0);
            $table->double('grandtotal_setelah_diskon')->nullable()->default(0)->comment('sudah termasuk ppn jika ada');

            $table->timestamps();
        });

        Schema::connection('second_mysql')->create('penjualan_invoice_berkas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penjualan_invoice_id');
            $table->foreign('penjualan_invoice_id')->references('id')->on('penjualan_invoice')->onDelete('cascade');

            $table->string('nama_berkas')->nullable();
            $table->timestamps();
        });

        Schema::connection('second_mysql')->create('penjualan_invoice_rinci', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penjualan_invoice_id');
            $table->foreign('penjualan_invoice_id')->references('id')->on('penjualan_invoice')->onDelete('cascade');

            $table->unsignedBigInteger('produk_id');
            $table->unsignedBigInteger('gudang_id');

            $table->double('kuantitas')->default(0);
            $table->double('harga_produk')->default(0);
            $table->double('diskon_persen')->default(0);
            $table->double('diskon_nominal')->default(0);
            $table->double('potongan_admin')->default(0);
            $table->double('cashback')->default(0);
            $table->double('subtotal')->default(0);
            $table->string('catatan')->nullable();
            $table->string('status')->default(0);

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
        Schema::dropIfExists('penjualan_invoice_berkas');
        Schema::dropIfExists('penjualan_invoice_rinci');
        Schema::dropIfExists('penjualan_invoice');
    }
}
