<?php

namespace App\Models\v2\Persediaan;

use App\Models\User;
use App\Models\v2\Master\Gudang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PindahStok extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'pindah_stok';
    protected $guarded = [];

    public function rincianProduk()
    {
        return $this->hasMany(PindahStokRinci::class,'pindah_stok_id');
    }

    public function dibuatOleh()
    {
        return $this->setConnection('mysql')->belongsTo(User::class,'created_by');
    }

    public function gudangAsal()
    {
        return $this->belongsTo(Gudang::class,'gudang_asal_id');
    }

    public function gudangTujuan()
    {
        return $this->belongsTo(Gudang::class,'gudang_tujuan_id');
    }

    public function berkas()
    {
        return $this->hasMany(PindahStokBerkas::class,'pindah_stok_id');
    }
}