<?php

namespace App\Models\v2\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPenjualan extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'jenis_penjualan';
}
