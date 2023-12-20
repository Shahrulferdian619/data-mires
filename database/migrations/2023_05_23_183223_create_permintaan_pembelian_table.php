<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermintaanPembelianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('second_mysql')->create('permintaan_pembelian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by');

            $table->string('tipe_permintaan')->default(1)->comment('1=produk,2=asset,3=jasa,4=lainnya');
            $table->string('nomer_permintaan_pembelian')->unique();
            $table->date('tanggal');
            $table->text('keterangan')->nullable();

            $table->string('status_revisi')->default(0)->comment('0=tidak revisi,1=revisi');
            $table->string('nomer_ref_revisi')->nullable()->comment('mengambil dari nomer revisi yang dipilih');

            $table->string('status_proses')->default(0)->comment('0=belum diproses,1=sudah diproses,10=ditutup');

            $table->unsignedBigInteger('direktur_id')->nullable();
            $table->unsignedBigInteger('komisaris_id')->nullable();
            $table->string('approve_direktur')->default(0);
            $table->string('approve_komisaris')->default(0);

            $table->text('catatan_direktur')->nullable();
            $table->text('catatan_komisaris')->nullable();

            $table->text('alasan_revisi')->nullable()->comment('jika permintaan pembelian sudah diproses, alasan revisi wajib diisi');
            $table->timestamps();
        });

        Schema::connection('second_mysql')->create('permintaan_pembelian_rinci', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permintaan_pembelian_id');
            $table->foreign('permintaan_pembelian_id')->references('id')->on('permintaan_pembelian')->onDelete('cascade');

            $table->unsignedBigInteger('item_id');
            $table->string('deskripsi_item')->nullable()->comment('untuk jenis barang jasa/lainnya');
            $table->double('kuantitas')->default(0);
            $table->double('kuantitas_diterima')->default(0)->comment('diisi ketika ada Penerimaan Barang');
            $table->double('kuantitas_diproses')->default(0)->comment('diisi ketika membuat PO');
            $table->double('harga')->nullable()->default(0);
            $table->string('catatan')->nullable()->default(null);
            $table->date('tanggal_minta');
            $table->timestamps();
        });

        Schema::connection('second_mysql')->create('permintaan_pembelian_berkas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permintaan_pembelian_id');
            $table->foreign('permintaan_pembelian_id')->references('id')->on('permintaan_pembelian')->onDelete('cascade');

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
        Schema::dropIfExists('permintaan_pembelian_berkas');
        Schema::dropIfExists('permintaan_pembelian_rinci');
        Schema::dropIfExists('permintaan_pembelian');
    }
}
