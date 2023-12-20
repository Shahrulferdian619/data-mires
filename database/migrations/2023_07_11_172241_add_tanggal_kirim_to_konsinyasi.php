<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTanggalKirimToKonsinyasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('konsinyasi', function (Blueprint $table) {
            $table->date('tanggal_kirim')->after('tanggal_konsinyasi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('konsinyasi', function (Blueprint $table) {
            $table->dropColumn('tanggal_kirim');
        });
    }
}
