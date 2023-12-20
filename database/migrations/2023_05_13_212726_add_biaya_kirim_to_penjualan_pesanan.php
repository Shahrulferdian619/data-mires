<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBiayaKirimToPenjualanPesanan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penjualan_pesanan', function (Blueprint $table) {
            $table->double('biaya_kirim')->nullable()->default(0)->after('diskon_global');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penjualan_pesanan', function (Blueprint $table) {
            $table->dropColumn(['biaya_kirim']);
        });
    }
}
