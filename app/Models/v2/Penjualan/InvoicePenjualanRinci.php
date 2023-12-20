<?php

namespace App\Models\v2\Penjualan;

use App\Models\v2\Persediaan\Barang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePenjualanRinci extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'penjualan_invoice_rinci';
    protected $guarded = [];

    public function produk()
    {
        return $this->setConnection('mysql')->belongsTo(Barang::class,'produk_id');
    }
}
