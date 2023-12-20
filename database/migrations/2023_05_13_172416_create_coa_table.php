<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('second_mysql')->create('coa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coa_tipe_id');
            $table->string('nomer_coa')->unique();
            $table->string('nama_coa')->unique();
            $table->string('keterangan')->nullable();
            $table->double('saldo_awal')->default(0);
            $table->string('status_aktif')->default(1)->comment('1=aktif, 0=non-aktif');
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
        Schema::dropIfExists('coa');
    }
}
