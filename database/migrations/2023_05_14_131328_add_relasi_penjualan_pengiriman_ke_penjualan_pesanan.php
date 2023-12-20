<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelasiPenjualanPengirimanKePenjualanPesanan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penjualan_pengiriman', function (Blueprint $table) {
            $table->foreign('penjualan_pesanan_id')->references('id')->on('penjualan_pesanan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penjualan_pengiriman', function (Blueprint $table) {
            //
        });
    }
}
