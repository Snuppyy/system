<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Prison
 *
 * @property int $id
 * @property int $author
 * @property int $project
 * @property int $region
 * @property string $encoding
 * @property string $name_ru
 * @property string|null $name_en
 * @property string|null $name_uz
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prison newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prison newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prison query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prison whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prison whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prison whereEncoding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prison whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prison whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prison whereNameRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prison whereNameUz($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prison whereProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prison whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prison whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prison whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Prison extends Model
{
    protected $fillable = [
        'name_ru', 'name_en', 'name_uz', 'encoding', 'author', 'project', 'status',
    ];
}
