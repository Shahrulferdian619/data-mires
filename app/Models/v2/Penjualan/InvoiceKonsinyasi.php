<?php

namespace App\Models\v2\Penjualan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceKonsinyasi extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'penjualan_konsinyasi';
    protected $guarded = [];
}
