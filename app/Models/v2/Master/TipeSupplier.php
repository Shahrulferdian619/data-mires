<?php

namespace App\Models\v2\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeSupplier extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'tipe_supplier';
    protected $guarded = [];
}
