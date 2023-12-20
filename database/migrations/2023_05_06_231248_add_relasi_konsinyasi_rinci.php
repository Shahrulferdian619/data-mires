<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelasiKonsinyasiRinci extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('konsinyasi_rinci', function (Blueprint $table) {
            $table->foreign('konsinyasi_id')
                ->references('id')
                ->on('konsinyasi')
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
        Schema::table('konsinyasi_rinci', function (Blueprint $table) {
            //
        });
    }
}
