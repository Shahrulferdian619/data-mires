<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJurnalUmumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jurnal_umum', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');

            $table->string('nomer')->unique();
            $table->date('tanggal');

            $table->double('total');

            $table->text('keterangan')->nullable();

            $table->timestamps();
        });

        Schema::create('jurnal_umum_rinci', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('jurnal_umum_id');
            $table->foreign('jurnal_umum_id')->references('id')
                ->on('jurnal_umum')
                ->onDelete('cascade');

            $table->unsignedBigInteger('coa_id');
            $table->foreign('coa_id')->references('id')
                ->on('coa');

            $table->double('debit');
            $table->double('kredit');
            $table->string('catatan')->nullable();

            $table->timestamps();
        });

        Schema::create('jurnal_umum_berkas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('jurnal_umum_id');
            $table->foreign('jurnal_umum_id')->references('id')
                ->on('jurnal_umum')
                ->onDelete('cascade');

            $table->string('nama_berkas')->nullable();

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
        Schema::dropIfExists('jurnal_umum_berkas');
        Schema::dropIfExists('jurnal_umum_rinci');
        Schema::dropIfExists('jurnal_umum');
    }
}
