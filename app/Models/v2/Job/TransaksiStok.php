<?php

namespace App\Models\v2\Job;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiStok extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'transaksi_stok';
    protected $guarded = [];
}
