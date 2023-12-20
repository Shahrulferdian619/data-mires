<?php

namespace App\Models\v2\Master\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'sales';
    protected $guarded = ['id'];
}
