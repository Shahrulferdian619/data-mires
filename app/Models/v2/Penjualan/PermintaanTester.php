<?php

namespace App\Models\v2\Penjualan;

use App\Models\Sales;
use App\Models\User;
use App\Models\v2\Master\Gudang;
use App\Models\v2\Master\Pelanggan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanTester extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'penjualan_tester';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function scopeGetData($query)
    {
        return $query->with('rincian', 'pelanggan', 'user')->orderBy('created_at', 'desc');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function rincian()
    {
        return $this->hasMany(PermintaanTesterRinci::class, 'penjualan_tester_id');
    }

    public function berkas()
    {
        return $this->hasMany(PermintaanTesterBerkas::class, 'penjualan_tester_id');
    }

    public function user()
    {
        return $this->setConnection('mysql')->belongsTo(User::class, 'created_by');
    }

    public function sales()
    {
        return $this->setConnection('mysql')->belongsTo(Sales::class, 'sales_id');
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }
}
