<?php

namespace App\Models\v2\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;
 
    protected $connection = 'second_mysql';
    protected $table = 'pelanggan';
    protected $guarded = ['id','created_at','updated_at'];

    public function scopeKodeTerakhir($query)
    {
        return $query->where('status_aktif',1)->orderBy('kode_pelanggan','desc')->first()->kode_pelanggan;
    }

    public function scopeActive($query)
    {
        return $query->where('status_aktif',1);
    }
}
