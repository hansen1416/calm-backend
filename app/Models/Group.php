<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'tags';

    protected $fillable = [
        'user_id',
        'name',
    ];
}
