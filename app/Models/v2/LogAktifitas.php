<?php

namespace App\Models\v2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktifitas extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $fillable = ['nama_user','nama_aktifitas'];
}
