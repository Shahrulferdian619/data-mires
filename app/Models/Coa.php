<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coa extends Model
{
    protected $table = 'coa';
    protected $guarded = [];

    use HasFactory;

    public function tipeCoa()
    {
        return $this->belongsTo(TipeCoa::class, 'id_coatype');
    }
}
