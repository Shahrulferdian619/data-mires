<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNomerRefToPenjualanInvoice extends Migration
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
            $table->string('nomer_ref')->nullable()->default(null)->after('nomer_invoice_penjualan');
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
            $table->dropColumn('nomer_ref');
        });
    }
}
