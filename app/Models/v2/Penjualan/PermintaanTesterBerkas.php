<?php

namespace App\Models\v2\Penjualan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanTesterBerkas extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'penjualan_tester_berkas';
    protected $guarded = ['id','created_at','updated_at'];
}
