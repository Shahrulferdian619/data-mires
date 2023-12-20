<?php

namespace App\Models\v2\Pembelian;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanPembelianBerkas extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'permintaan_pembelian_berkas';
    protected $guarded = [];
}
