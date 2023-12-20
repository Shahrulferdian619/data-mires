<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropForeignPenjualanPesananIdToPenjualanInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penjualan_invoice', function (Blueprint $table) {
            //
            $table->dropForeign('penjualan_invoice_penjualan_pesanan_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penjualan_invoice', function (Blueprint $table) {
            //
        });
    }
}
