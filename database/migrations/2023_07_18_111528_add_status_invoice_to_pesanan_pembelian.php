<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusInvoiceToPesananPembelian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pesanan_pembelian', function (Blueprint $table) {
            $table->string('status_invoice',1)->default(0)->after('status_proses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pesanan_pembelian', function (Blueprint $table) {
            $table->dropColumn('status_invoice');
        });
    }
}
