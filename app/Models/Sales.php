<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use SoftDeletes;

    protected $table = 'sales';
}
