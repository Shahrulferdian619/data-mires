<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBukubankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bukubank', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bank_id');
            $table->foreign('bank_id')->references('id')->on('coa');

            $table->date('tanggal');
            $table->string('nomer_sumber');
            $table->string('nomer_ref')->nullable();
            $table->string('tipe_transaksi');
            $table->text('keterangan')->nullable();
            $table->double('nominal_mutasi');
            $table->string('tipe_mutasi');
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
        Schema::dropIfExists('bukubank');
    }
}
