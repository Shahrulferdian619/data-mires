<?php

namespace App\Models\v2\Penjualan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanPenjualanRinci extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'penerimaan_penjualan_rinci';
    protected $guarded = [];

    public function rincianPotongan()
    {
        return $this->hasMany(PotonganPenerimaanPenjualan::class,'penerimaan_penjualan_rinci_id');
    }

    public function invoice()
    {
        return $this->belongsTo(InvoicePenjualan::class,'penjualan_invoice_id');
    }
}
