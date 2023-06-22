<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Responsibilities
 *
 * @property int $id
 * @property int $author
 * @property array|null $position
 * @property int|null $type
 * @property string $name_ru
 * @property string|null $name_uz
 * @property string|null $name_en
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Responsibilities newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Responsibilities newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Responsibilities query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Responsibilities whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Responsibilities whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Responsibilities whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Responsibilities whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Responsibilities whereNameRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Responsibilities whereNameUz($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Responsibilities wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Responsibilities whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Responsibilities whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Responsibilities whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Responsibilities extends Model
{
    protected $fillable = [
        'author',
        'position',
        'type',
        'name_ru',
        'name_uz',
        'name_en',
        'status'
    ];

    protected $casts = [
        'position' => 'array',
    ];
}
