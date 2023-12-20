<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasFaktur extends Model
{
    use HasFactory;
    protected $table = 'berkas_faktur';
    protected $guarded = [];
}
