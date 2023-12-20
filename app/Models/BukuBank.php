<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuBank extends Model
{
    protected $table = 'buku_bank';
    protected $guarded = [];
    protected $keyType = 'string';

    use HasFactory;
}
