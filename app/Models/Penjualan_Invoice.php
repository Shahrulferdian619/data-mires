<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan_Invoice extends Model
{
    protected $table = 'penjualan_invoice';
    use HasFactory;
    protected $guarded = [];

    public function rinci()
    {
        return $this->hasMany('\App\Models\Penjualan_Invoice_Rinci', 'penjualan_invoice_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo('\App\Models\Pelanggan', 'pelanggan_id');
    }

    public function so()
    {
        return $this->belongsTo('\App\Models\Penjualan_SO', 'so_id');
    }
}
