<?php

namespace App\Models\v2\Penjualan;

use App\Models\v2\Master\Pelanggan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengirimanPenjualan extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'penjualan_pengiriman';
    protected $guarded = ['id'];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function rincian()
    {
        return $this->hasMany(PengirimanPenjualanRinci::class,'penjualan_pengiriman_id');
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class,'penjualan_pesanan_id');
    }
}
