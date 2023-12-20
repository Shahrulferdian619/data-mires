<?php

namespace App\Models\v2\Pembelian;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanPembelian extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'permintaan_pembelian';
    protected $guarded = [];

    public function scopeBelumDiproses($query)
    {
        return $query->where('status_proses', 0)->orderBy('tanggal', 'asc');
    }

    public function scopeDiapprove($query)
    {
        return $query->where('approve_direktur', 1)->orderBy('tanggal', 'asc');
    }

    public function scopeGetData($query)
    {
        return $query->where('status_delete', 0)->orderBy('tanggal', 'DESC');
    }

    public function rincianPermintaan()
    {
        return $this->hasMany(PermintaanPembelianRinci::class, 'permintaan_pembelian_id');
    }

    public function sudahDiproses($status)
    {
        return $this->update(['status_proses' => $status]);
    }

    public function berkasPermintaan()
    {
        return $this->hasMany(PermintaanPembelianBerkas::class, 'permintaan_pembelian_id');
    }

    public function dibuatOleh()
    {
        return $this->setConnection('mysql')->belongsTo(User::class, 'created_by');
    }

    public function approveDirektur()
    {
        return $this->setConnection('mysql')->belongsTo(User::class, 'direktur_id');
    }
}
