<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanCR_Rinci extends Model
{
    use HasFactory;
    protected $table = 'penjualan_cr_rinci';

    public function cr()
    {
        return $this->belongsTo('\App\Models\PenjualanCR', 'penjualan_cr_id');
    }
    public function invoice()
    {
        return $this->belongsTo('\App\Models\Penjualan_Invoice', 'penjualan_invoice_id');
    }
}
