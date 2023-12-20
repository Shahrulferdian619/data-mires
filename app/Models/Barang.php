<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barangs';
    protected $guarded = [];
    protected $fillable = ['kategoribarang_id', 'kode_barang', 'nama_barang', 'aktif'];

    public function kategoribarang()
    {
        return $this->belongsTo(Kategoribarang::class);
    }
}
