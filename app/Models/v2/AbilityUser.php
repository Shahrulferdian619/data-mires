<?php

namespace App\Models\v2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbilityUser extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'ability_user';
}
