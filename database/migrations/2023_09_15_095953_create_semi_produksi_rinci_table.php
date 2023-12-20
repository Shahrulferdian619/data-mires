<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSemiProduksiRinciTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('semi_produksi_rinci', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_semi_produksi');
            $table->foreign('id_semi_produksi')->references('id')->on('semi_produksi')->onDelete('cascade');
            
            $table->unsignedBigInteger('barang_id');
            $table->foreign('barang_id')->references('id')->on('barang')->onDelete('cascade');

            $table->integer('kuantitas');
            $table->text('catatan')->nullable();
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
        Schema::dropIfExists('semi_produksi_rinci');
    }
}
