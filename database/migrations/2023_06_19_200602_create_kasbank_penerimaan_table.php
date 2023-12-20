<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKasbankPenerimaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('second_mysql')->create('kasbank_penerimaan', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('created_by');
            
            $table->unsignedBigInteger('bank_id');
            $table->foreign('bank_id')->references('id')->on('coa');

            $table->string('nomer')->unique();
            $table->date('tanggal');

            $table->double('nominal')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::connection('second_mysql')->create('kasbank_penerimaan_rinci', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coa_id');
            $table->foreign('coa_id')->references('id')->on('coa');

            $table->unsignedBigInteger('kasbank_penerimaan_id');
            $table->foreign('kasbank_penerimaan_id')->references('id')->on('kasbank_penerimaan')->onDelete('cascade');

            $table->double('nominal')->default(0);
            $table->string('catatan')->nullable();
            $table->timestamps();
        });

        Schema::connection('second_mysql')->create('kasbank_penerimaan_berkas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kasbank_penerimaan_id');
            $table->foreign('kasbank_penerimaan_id')->references('id')->on('kasbank_penerimaan')->onDelete('cascade');

            $table->string('nama_berkas');
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
        Schema::dropIfExists('kasbank_penerimaan_berkas');
        Schema::dropIfExists('kasbank_penerimaan_rinci');
        Schema::dropIfExists('kasbank_penerimaan');
    }
}
