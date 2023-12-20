<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiStokTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('second_mysql')->create('transaksi_stok', function (Blueprint $table) {
            $table->id();
            $table->string('nomer_ref')->nullable();
            $table->unsignedBigInteger('gudang_id');
            $table->unsignedBigInteger('produk_id');
            $table->string('keterangan')->nullable();
            $table->double('in');
            $table->double('out');
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
        Schema::dropIfExists('transaksi_stok');
    }
}
