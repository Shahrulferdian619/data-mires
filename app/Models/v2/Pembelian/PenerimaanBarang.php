<?php

namespace App\Models\v2\Pembelian;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanBarang extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'penerimaan_barang';
    protected $guarded = [];

    public function rincianBarang()
    {
        return $this->hasMany(PenerimaanBarangRinci::class, 'penerimaan_barang_id');
    }

    public function rincianBerkas()
    {
        return $this->hasMany(PenerimaanBarangBerkas::class, 'penerimaan_barang_id');
    }

    public function pesananPembelian()
    {
        return $this->belongsTo(PesananPembelian::class, 'pesanan_pembelian_id');
    }
}
