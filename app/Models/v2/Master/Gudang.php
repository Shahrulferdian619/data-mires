<?php

namespace App\Models\v2\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'gudang';
    protected $guarded = ['id','created_at','updated_at'];
}
