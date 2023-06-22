<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExportTempExcel extends Model
{
    protected $fillable = [
        'id',
        'user',
        'comment',
        'date',
        'start',
        'end',
        'result',
        'author'
    ];
}
