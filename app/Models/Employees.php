<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    use SoftDeletes;

    protected $table = 'employees';
    protected $dates = ['deleted_at'];
}
