<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan_SO_rinci extends Model
{
    use HasFactory;

    protected $table = 'penjualan_so_rinci';
    protected $guarded = [];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
