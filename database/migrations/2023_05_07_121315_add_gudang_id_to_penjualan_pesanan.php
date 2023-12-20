<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGudangIdToPenjualanPesanan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penjualan_pesanan', function (Blueprint $table) {
            $table->unsignedBigInteger('gudang_id')->after('created_by')->default(1)->nullable();
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
            $table->dropColumn(['gudang_id']);
        });
    }
}
