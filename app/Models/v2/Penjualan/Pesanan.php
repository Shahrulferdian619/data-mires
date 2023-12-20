<?php

namespace App\Models\v2\Penjualan;

use App\Models\Sales;
use App\Models\v2\Master\Coa;
use App\Models\v2\Master\Gudang;
use App\Models\v2\Master\Pelanggan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'penjualan_pesanan';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function scopeBelumInvoice($query)
    {
        return $query->whereMonth('tanggal', 5)->whereYear('tanggal', 2023)->whereDoesntHave('invoice');
    }

    public function invoice()
    {
        return $this->hasOne(InvoicePenjualan::class,'penjualan_pesanan_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class,'pelanggan_id');
    }

    public function sales()
    {
        return $this->setConnection('mysql')->belongsTo(Sales::class,'sales_id');
    }

    public function rincian()
    {
        return $this->hasMany(PesananRinci::class,'penjualan_pesanan_id');
    }

    public function berkas()
    {
        return $this->hasOne(PesananBerkas::class,'penjualan_pesanan_id');
    }

    public function pengiriman()
    {
        return $this->hasOne(PengirimanPenjualan::class,'penjualan_pesanan_id');
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class,'gudang_id');
    }

    public function akun_bank()
    {
        return $this->belongsTo(Coa::class,'akun_bank_id');
    }

    public function akun_ppn()
    {
        return $this->belongsTo(Coa::class,'akun_ppn_id');
    }

    public function akun_biayakirim()
    {
        return $this->belongsTo(Coa::class,'akun_biayakirim_id');
    }

    public function akun_diskon()
    {
        return $this->belongsTo(Coa::class,'akun_diskon_id');
    }    
}
