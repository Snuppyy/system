<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class indicatorStats extends Model
{
    protected $fillable = [
        'author',
        'indicators',
        'status'
    ];

    protected $casts = [
        'indicators' => 'array'
    ];
}
