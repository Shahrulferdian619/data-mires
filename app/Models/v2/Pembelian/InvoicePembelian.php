<?php

namespace App\Models\v2\Pembelian;

use App\Models\v2\Master\Supplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePembelian extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'invoice_pembelian';
    protected $guarded = [];

    public function pesananPembelian()
    {
        return $this->belongsTo(PesananPembelian::class, 'pesanan_pembelian_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function rincianItem()
    {
        return $this->hasMany(InvoicePembelianRinci::class, 'invoice_pembelian_id');
    }

    public function rincianBerkas()
    {
        return $this->hasMany(InvoicePembelianBerkas::class, 'invoice_pembelian_id');
    }
}
