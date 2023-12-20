<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicePembelianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_pembelian', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('pesanan_pembelian_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->string('status_bayar',1)->default(0);
            
            $table->string('nomer_invoice_pembelian')->unique();
            $table->date('tanggal');

            $table->double('diskon_persen_global')->default(0)->nullable();
            $table->double('diskon_nominal_global')->default(0)->nullable();

            $table->string('ppn')->default(0)->comment('0=non-ppn, 1=ppn, 2=include-ppn');
            $table->double('nilai_ppn')->default(0)->nullable();

            $table->double('biaya_kirim')->default(0);

            $table->string('pajaklain1_keterangan')->nullable();
            $table->double('pajaklain1_persen')->default(0);
            $table->double('pajaklain1_nominal')->default(0);

            $table->double('total');
            $table->double('total_setelah_diskon');
            $table->double('grandtotal');
            $table->double('sudah_terbayar')->default(0);

            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('invoice_pembelian_rinci', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_pembelian_id');
            $table->foreign('invoice_pembelian_id')
                ->references('id')
                ->on('invoice_pembelian')
                ->onDelete('cascade');

            $table->unsignedBigInteger('item_id');
            $table->string('deskripsi_item')->nullable();
            $table->double('kuantitas');
            $table->double('harga')->default(0);
            $table->double('diskon_persen')->default(0);
            $table->double('diskon_nominal')->default(0);
            $table->double('subtotal')->default(0);
            $table->string('catatan')->nullable();

            $table->timestamps();
        });

        Schema::create('invoice_pembelian_berkas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_pembelian_id');
            $table->foreign('invoice_pembelian_id')
                ->references('id')
                ->on('invoice_pembelian')
                ->onDelete('cascade');

            $table->string('nama_berkas')->nullable();

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
        Schema::dropIfExists('invoice_pembelian_berkas');
        Schema::dropIfExists('invoice_pembelian_rinci');
        Schema::dropIfExists('invoice_pembelian');
    }
}
