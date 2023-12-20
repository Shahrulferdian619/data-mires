<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSudahTerbayarToPenjualanInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penjualan_invoice', function (Blueprint $table) {
            $table->double('sudah_terbayar')->default(0)->after('grandtotal_setelah_diskon');
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
            $table->dropColumn('sudah_terbayar');
        });
    }
}
