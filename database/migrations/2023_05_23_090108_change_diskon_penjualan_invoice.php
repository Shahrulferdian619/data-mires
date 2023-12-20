<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDiskonPenjualanInvoice extends Migration
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
            $table->renameColumn('diskon_persen', 'diskon_persen_global');
            $table->renameColumn('diskon_global', 'diskon_nominal_global');
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
