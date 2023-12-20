<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan_DO extends Model
{
    protected $table = 'penjualan_do';
    use HasFactory;
    protected $guarded = [];

    public function pelanggan()
    {
        return $this->belongsTo('\App\Models\Pelanggan', 'id_pelanggan');
    }

    public function rinci()
    {
        return $this->hasMany('\App\Models\Penjualan_DO_Rinci', 'do_id');
    }

    public function so()
    {
        return $this->belongsTo('\App\Models\Penjualan_SO', 'so_id');
    }

    public function berkas()
    {
        return $this->hasOne('\App\Models\BerkasDeliveryorder', 'penjualan_do_id');
    }
}
