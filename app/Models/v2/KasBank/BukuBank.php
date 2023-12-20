<?php

namespace App\Models\v2\KasBank;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuBank extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'bukubank';
    protected $guarded = [];
    
}
