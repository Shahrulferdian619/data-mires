<?php

namespace App\Models\v2\Produksi;

use App\Models\v2\Master\Barang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemiProduksiRincian extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'semi_produksi_rinci';
    protected $guarded = [];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function semiProduksi()
    {
        return $this->belongsTo(SemiProduksi::class);
    }
}
