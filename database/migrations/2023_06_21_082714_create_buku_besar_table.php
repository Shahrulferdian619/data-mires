<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBukuBesarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bukubesar', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('coa_id');
            $table->foreign('coa_id')->references('id')->on('coa');

            $table->unsignedBigInteger('sumber_id');

            $table->string('tahun');
            $table->string('tanggal');

            $table->string('nomer_sumber');
            $table->string('sumber_transaksi');

            $table->double('nominal');
            $table->string('tipe_mutasi');

            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bukubesar');
    }
}
