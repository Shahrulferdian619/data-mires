<?php

namespace App\Models\v2\Master\Kategori;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPelanggan extends Model
{
    use HasFactory;

    
    protected $connection = 'second_mysql';
    protected $table = 'kategori_pelanggan';
    protected $guarded = [];

    
    // public function scopeActive($query)
    // {
    //     return $query->where('status_aktif',1);
    // }
}
