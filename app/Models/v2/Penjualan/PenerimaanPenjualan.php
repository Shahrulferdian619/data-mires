<?php

namespace App\Models\v2\Penjualan;

use App\Models\v2\Master\Coa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanPenjualan extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'penerimaan_penjualan';
    protected $guarded = [];

    public function rincianPenerimaan()
    {
        return $this->hasMany(PenerimaanPenjualanRinci::class,'penerimaan_penjualan_id');
    }

    public function bank()
    {
        return $this->belongsTo(Coa::class,'akun_bank_id');
    }

    public function berkas()
    {
        return $this->hasMany(PenerimaanPenjualanBerkas::class,'penerimaan_penjualan_id');
    }
}
