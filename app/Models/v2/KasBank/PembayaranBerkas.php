<?php

namespace App\Models\v2\KasBank;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranBerkas extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'kasbank_pembayaran_berkas';
    protected $guarded = [];
}
