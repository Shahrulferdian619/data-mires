<?php

namespace App\Models\v2\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpdateStok extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'stok_produk_gudang';
}
