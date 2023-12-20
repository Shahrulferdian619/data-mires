<?php

namespace App\Models\v2\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriBarang extends Model
{
    use HasFactory;


    protected $connection = 'second_mysql';
    protected $table = 'kategori_barang';
    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('aktif', 1);
    }

    public function barang()
    {
        return $this->hasMany(Barang::class);
    }
}
