<?php

namespace App\Models\v2\Pembelian;

use App\Models\v2\Persediaan\Barang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePembelianRinci extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'invoice_pembelian_rinci';
    protected $guarded = [];

    public function item()
    {
        return $this->setConnection('mysql')->belongsTo(Barang::class, 'item_id');
    }
}
