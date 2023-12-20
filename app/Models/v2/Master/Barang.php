<?php

namespace App\Models\v2\Master;

use App\Models\v2\Produksi\SemiProduksi;
use App\Models\v2\Produksi\SemiProduksiRincian;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'barang';
    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('aktif', 1);
    }

    public function scopeType($query)
    {
        return $query->where('type', 1);
    }

    public function kategoriBarang()
    {
        return $this->belongsTo(KategoriBarang::class);
    }

    public function semiProduk()
    {
        return $this->hasMany(SemiProduksi::class, 'barang_id');
    }

    public function semiProdukRinci()
    {
        return $this->hasMany(SemiProduksiRincian::class, 'barang_id');
    }
}
