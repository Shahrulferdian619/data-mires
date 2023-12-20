<?php

namespace App\Models\v2\Pembelian;

use App\Models\User;
use App\Models\v2\Master\Supplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananPembelian extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'pesanan_pembelian';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function scopeSudahDisetujui($query)
    {
        return $query->where('approve_direktur', 1)
            ->where('approve_komisaris', 1)
            ->where('status_proses', 0);
    }

    public function dibuatOleh()
    {
        return $this->setConnection('mysql')->belongsTo(User::class, 'created_by');
    }

    public function approveDirektur()
    {
        return $this->setConnection('mysql')->belongsTo(User::class, 'direktur_id');
    }

    public function approveKomisaris()
    {
        return $this->setConnection('mysql')->belongsTo(User::class, 'komisaris_id');
    }

    public function rincianItem()
    {
        return $this->hasMany(PesananPembelianRinci::class, 'pesanan_pembelian_id');
    }

    public function rincianBerkas()
    {
        return $this->hasMany(PesananPembelianBerkas::class, 'pesanan_pembelian_id');
    }

    public function permintaanPembelian()
    {
        return $this->belongsTo(PermintaanPembelian::class, 'permintaan_pembelian_id');
    }

    public function ditutup($status)
    {
        return $this->update([
            'status_proses' => $status,
        ]);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
