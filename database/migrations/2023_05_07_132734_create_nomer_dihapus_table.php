<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNomerDihapusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('second_mysql')->create('nomer_dihapus', function (Blueprint $table) {
            $table->id();
            $table->string('nama_modul')->nullable();
            $table->string('nomer')->nullable();
            $table->string('sudah_dipakai')->default(0);
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
        Schema::dropIfExists('nomer_dihapus');
    }
}
