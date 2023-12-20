<?php

namespace App\Models\v2\Penjualan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PotonganPenerimaanPenjualan extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'potongan_penerimaan_penjualan';
    protected $guarded = [];
}
