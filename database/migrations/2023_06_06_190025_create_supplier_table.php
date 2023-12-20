<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipe_supplier_id');
            $table->foreign('tipe_supplier_id','fk_tipe_supplier')->references('id')->on('tipe_supplier');

            $table->string('kode')->unique();
            $table->string('nama')->unique();
            $table->string('nama_pic')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('no_telp');
            $table->string('provinsi')->nullable();
            $table->string('kota')->nullable();
            $table->text('detil_alamat')->nullable();
            $table->string('nomer_rekening')->nullable();
            $table->string('status_aktif')->default(1);
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
        Schema::dropIfExists('supplier');
    }
}
