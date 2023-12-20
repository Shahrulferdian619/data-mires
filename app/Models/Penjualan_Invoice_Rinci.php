<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan_Invoice_Rinci extends Model
{
    protected $table = 'penjualan_invoice_rinci';
    use HasFactory;
    protected $guarded = [];

    public function barang()
    {
        return $this->belongsTo('\App\Models\Barang', 'barang_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Penjualan_Invoice::class, 'penjualan_invoice_id');
    }

}
