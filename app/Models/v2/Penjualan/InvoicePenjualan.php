<?php

namespace App\Models\v2\Penjualan;

use App\Models\Sales;
use App\Models\v2\Master\Coa;
use App\Models\v2\Master\Gudang;
use App\Models\v2\Master\Pelanggan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePenjualan extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'penjualan_invoice';
    protected $guarded = [];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'penjualan_pesanan_id');
    }

    public function sales()
    {
        return $this->setConnection('mysql')->belongsTo(Sales::class,'sales_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function rincian()
    {
        return $this->hasMany(InvoicePenjualanRinci::class, 'penjualan_invoice_id');
    }

    public function berkas()
    {
        return $this->hasOne(InvoicePenjualanBerkas::class,'penjualan_invoice_id');
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }

    public function akun_bank()
    {
        return $this->belongsTo(Coa::class, 'akun_bank_id');
    }

    public function akun_ppn()
    {
        return $this->belongsTo(Coa::class, 'akun_ppn_id');
    }

    public function akun_biayakirim()
    {
        return $this->belongsTo(Coa::class, 'akun_biayakirim_id');
    }

    public function akun_diskon()
    {
        return $this->belongsTo(Coa::class, 'akun_diskon_id');
    }
}
