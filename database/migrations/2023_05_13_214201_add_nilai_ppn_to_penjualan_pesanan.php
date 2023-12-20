<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNilaiPpnToPenjualanPesanan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penjualan_pesanan', function (Blueprint $table) {
            $table->double('nilai_ppn')->nullable()->default(0)->after('ppn');
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
            $table->dropColumn('nilai_ppn');
        });
    }
}
