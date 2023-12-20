<?php

namespace App\Models\v2\Pembelian;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanBarangBerkas extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'penerimaan_barang_berkas';
    protected $guarded = [];
}
