<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSemiProduksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('semi_produksi', function (Blueprint $table) {
            $table->id();
            $table->string('nomer_produksi')->unique();
            $table->date('tanggal_produksi');
            $table->text('keterangan')->nullable();
            $table->integer('created_by');
            $table->unsignedBigInteger('barang_id');
            $table->foreign('barang_id')->references('id')->on('barang')->onDelete('cascade');
            
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
        Schema::dropIfExists('semi_produksi');
    }
}
