<?php

namespace App\Models\v2\Pembelian;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePembelianBerkas extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'invoice_pembelian_berkas';
    protected $guarded = [];
}
