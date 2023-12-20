<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanCR extends Model
{
    use HasFactory;
    protected $table = 'penjualan_cr';

    public function pelanggan()
    {
        return $this->belongsTo('\App\Models\Pelanggan', 'pelanggan_id');
    }
    public function rinci()
    {
        return $this->hasMany('\App\Models\PenjualanCR_Rinci','penjualan_cr_id');
    }

    public function berkas()
    {
        return $this->belongsTo('\App\Models\BerkasCr', 'cr_id');
    }

    public function invoice()
    {
        return $this->belongsTo('\App\Models\Penjualan_Invoice', 'invoice_id');
    }
}
