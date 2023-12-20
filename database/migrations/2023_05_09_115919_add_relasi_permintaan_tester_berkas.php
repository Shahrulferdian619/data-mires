<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelasiPermintaanTesterBerkas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penjualan_tester_berkas', function (Blueprint $table) {
            $table->foreign('penjualan_tester_id')
                ->references('id')
                ->on('penjualan_tester')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penjualan_tester_berkas', function (Blueprint $table) {
            //
        });
    }
}
