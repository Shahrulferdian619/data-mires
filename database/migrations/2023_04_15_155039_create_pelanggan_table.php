<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelangganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('second_mysql')->create('pelanggan', function (Blueprint $table) {
            $table->id();
            $table->string('tipe_pelanggan')->nullable();
            
            $table->string('kode_pelanggan')->unique();
            $table->string('kode_area')->nullable();

            $table->string('nama_pelanggan')->unique();
            $table->string('no_handphone')->nullable();
            $table->string('email')->nullable();
            $table->string('detil_alamat')->nullable();
            $table->string('kota')->nullable();
            $table->string('provinsi')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('status_aktif')->default(1);
            $table->double('saldo')->nullable()->default(0);

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
        Schema::dropIfExists('pelanggan');
    }
}
