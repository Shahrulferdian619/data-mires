<?php

namespace App\Models\v2\Produksi;

use App\Models\v2\Master\Barang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemiProduksi extends Model
{
    use HasFactory;
    
    protected $connection = 'second_mysql';
    protected $table = 'semi_produksi';
    protected $guarded = [];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function semiProduksiRinci()
    {
        return $this->hasMany(SemiProduksiRincian::class, 'id_semi_produksi');
    }
}
