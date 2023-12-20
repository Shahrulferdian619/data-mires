<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStokProdukGudangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stok_produk_gudang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('updated_by')->comment('user terakhir yang melakukan update');
            $table->unsignedBigInteger('produk_id');
            $table->unsignedBigInteger('gudang_id');
            $table->string('nama_gudang');
            $table->string('kode_produk');
            $table->string('nama_produk');
            $table->double('kuantitas');
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
        Schema::dropIfExists('stok_produk_gudang');
    }
}
