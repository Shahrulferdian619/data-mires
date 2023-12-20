<?php

namespace App\Models\v2\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipecoa extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'coa_tipe';
}
