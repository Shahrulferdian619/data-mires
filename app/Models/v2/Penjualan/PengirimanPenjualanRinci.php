<?php

namespace App\Models\v2\Penjualan;

use App\Models\v2\Persediaan\Barang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengirimanPenjualanRinci extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'penjualan_pengiriman_rinci';
    protected $guarded = ['id'];

    public function produk()
    {
        return $this->setConnection('mysql')->belongsTo(Barang::class,'produk_id');
    }
}
