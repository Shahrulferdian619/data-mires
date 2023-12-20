<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePindahStokTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('second_mysql')->create('pindah_stok', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by');
            $table->string('nomer_ref')->unique();
            $table->date('tanggal');
            $table->date('tanggal_kirim')->comment('tanggal pengiriman / tanggal proses di sistem')->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('gudang_asal_id');
            $table->unsignedBigInteger('gudang_tujuan_id');
            $table->string('status_proses', 2)->default(0);
            $table->timestamps();
        });

        Schema::connection('second_mysql')->create('pindah_stok_rinci', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pindah_stok_id');
            $table->foreign('pindah_stok_id')->references('id')->on('pindah_stok')->onDelete('cascade');

            $table->unsignedBigInteger('produk_id');
            $table->double('kuantitas')->default(0);
            $table->string('catatan')->nullable();
            $table->timestamps();
        });

        Schema::connection('second_mysql')->create('pindah_stok_berkas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pindah_stok_id');
            $table->foreign('pindah_stok_id')->references('id')->on('pindah_stok')->onDelete('cascade');

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
        Schema::dropIfExists('pindah_stok_berkas');
        Schema::dropIfExists('pindah_stok_rinci');
        Schema::dropIfExists('pindah_stok');
    }
}
