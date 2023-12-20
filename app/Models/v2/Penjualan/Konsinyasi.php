<?php

namespace App\Models\v2\Penjualan;

use App\Models\v2\Master\Pelanggan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsinyasi extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'konsinyasi';
    protected $guarded = ['id','created_at','updated_at'];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class,'pelanggan_id');
        //return $this->setConnection('mysql')->belongsTo(Pelanggan::class,'pelanggan_id');
    }

    public function rinci()
    {
        return $this->hasMany(KonsinyasiRinci::class,'konsinyasi_id');
    }

    public function berkas()
    {
        return $this->hasOne(KonsinyasiBerkas::class,'konsinyasi_id');
    }
}
