<?php

namespace App\Models\v2\Penjualan;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonsinyasiRinci extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'konsinyasi_rinci';
    protected $guarded = ['id','created_at','updated_at'];

    public function produk()
    {
        return $this->setConnection('mysql')->belongsTo(Barang::class,'produk_id');
    }
}
