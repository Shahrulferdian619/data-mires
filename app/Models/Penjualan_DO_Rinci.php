<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan_DO_Rinci extends Model
{
    protected $table = 'penjualan_do_rinci';
    use HasFactory;
    protected $guarded = [];

    public function barang()
    {
        return $this->belongsTo('\App\Models\Barang', 'id_barang');
    }

    public function sorinciid()
    {
        return $this->belongsTo('\App\Models\Penjualan_SO_rinci', 'so_rinci_id');
    }
}
