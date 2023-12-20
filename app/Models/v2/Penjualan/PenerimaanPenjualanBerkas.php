<?php

namespace App\Models\v2\Penjualan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanPenjualanBerkas extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'penerimaan_penjualan_berkas';
    protected $guarded = [];
}
