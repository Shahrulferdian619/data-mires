<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan_SO extends Model
{
    use HasFactory;

    protected $table = 'penjualan_so';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function rinci()
    {
        return $this->hasMany('\App\Models\Penjualan_SO_rinci', 'id_so');
    }

    public function berkas()
    {
        return $this->hasOne('\App\Models\BerkasSalesorder', 'penjualan_so_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo('\App\Models\Pelanggan', 'id_pelanggan');
    }

    public function customer()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    public function sales()
    {
        return $this->belongsTo(Sales::class, 'id_sales');
    }

    public function jenisPenjualan()
    {
        return $this->belongsTo(JenisPenjualan::class, 'jenis_penjualan', 'id');
    }
}
