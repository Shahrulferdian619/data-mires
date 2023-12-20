<?php

namespace App\Models\v2\Pembelian;

use App\Models\v2\Persediaan\Barang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanBarangRinci extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'penerimaan_barang_rinci';
    protected $guarded = [];

    public function barang()
    {
        return $this->setConnection('mysql')->belongsTo(Barang::class, 'item_id');
    }
}
