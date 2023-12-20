<?php

namespace App\Models\v2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NomerDihapus extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'nomer_dihapus';
    protected $guarded = ['id','created_at','updated_at'];
}
