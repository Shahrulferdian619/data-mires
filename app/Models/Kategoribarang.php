<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategoribarang extends Model
{
    use HasFactory;
    
    protected $table = 'kategoribarangs';

    protected $fillable = ['nama_kategori','deskripsi_kategori','aktif'];

    public function barang()
    {
        return $this->hasMany(Barang::class);
    }

}
