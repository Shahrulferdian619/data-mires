<?php

namespace App\Models\v2\Persediaan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PindahStokRinci extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'pindah_stok_rinci';
    protected $guarded = [];

    public function produk()
    {
        return $this->setConnection('mysql')->belongsTo(Barang::class,'produk_id');
    }
}
