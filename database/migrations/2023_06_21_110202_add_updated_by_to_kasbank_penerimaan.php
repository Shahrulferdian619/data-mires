<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpdatedByToKasbankPenerimaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kasbank_penerimaan', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('updated_by')->after('created_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kasbank_penerimaan', function (Blueprint $table) {
            //
            $table->dropColumn('updated_by');
        });
    }
}
