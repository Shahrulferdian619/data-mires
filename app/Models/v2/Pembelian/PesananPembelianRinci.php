<?php

namespace App\Models\v2\Pembelian;

use App\Models\v2\Persediaan\Barang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananPembelianRinci extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'pesanan_pembelian_rinci';
    protected $guarded = [];

    public function pesanan()
    {
        return $this->belongsTo(PesananPembelian::class, 'pesanan_pembelian_id');
    }

    public function item()
    {
        return $this->setConnection('mysql')->belongsTo(Barang::class, 'item_id');
    }
}
