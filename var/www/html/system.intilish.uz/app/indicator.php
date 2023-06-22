<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class indicator extends Model
{
    protected $fillable = [
        'author',
        'name',
        'status'
    ];

}
