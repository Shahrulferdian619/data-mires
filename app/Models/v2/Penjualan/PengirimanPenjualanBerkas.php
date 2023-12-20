<?php

namespace App\Models\v2\Penjualan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengirimanPenjualanBerkas extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'penjualan_pengiriman_berkas';
    protected $guarded = ['id'];
}
