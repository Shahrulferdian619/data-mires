<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeSupplier extends Model
{
    use HasFactory;

    protected $table = 'tipesuppliers';

    public function suppliers()
    {
        return $this->hasMany('App\Models\Supplier');
    }
}
