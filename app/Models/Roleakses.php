<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roleakses extends Model
{
    use HasFactory;

    protected $table = 'role_akses';
    protected $fillable = [
        'nama_controller',
        'can_create',
        'can_read',
        'can_edit',
        'can_delete',
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
