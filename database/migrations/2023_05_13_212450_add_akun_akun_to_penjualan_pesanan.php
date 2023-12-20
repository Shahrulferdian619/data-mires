<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAkunAkunToPenjualanPesanan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penjualan_pesanan', function (Blueprint $table) {
            $table->unsignedBigInteger('akun_diskon_id')->nullable()->default(74)->after('id');
            $table->foreign('akun_diskon_id')->references('id')->on('coa')->onDelete('restrict');

            $table->unsignedBigInteger('akun_biayakirim_id')->nullable()->default(87)->after('id');
            $table->foreign('akun_biayakirim_id')->references('id')->on('coa')->onDelete('restrict');

            $table->unsignedBigInteger('akun_ppn_id')->nullable()->default(50)->after('id');
            $table->foreign('akun_ppn_id')->references('id')->on('coa')->onDelete('restrict');

            $table->unsignedBigInteger('akun_bank_id')->nullable()->default(4)->after('id');
            $table->foreign('akun_bank_id')->references('id')->on('coa')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penjualan_pesanan', function (Blueprint $table) {
            $table->dropColumn(['akun_diskon_id','akun_biayakirim_id','akun_ppn_id','akun_bank_id']);
        });
    }
}
