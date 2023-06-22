<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Client
 *
 * @property int $id
 * @property int $author
 * @property int $region
 * @property int $prison
 * @property int|null $tb
 * @property string $f_name
 * @property string $s_name
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereFName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client wherePrison($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereSName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereTb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Client extends Model
{
    protected $fillable = [
        'author',
        'region',
        'prison',
        'tb',
        'f_name',
        's_name',
        'status',
    ];
}
