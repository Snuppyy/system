<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Region
 *
 * @property int $id
 * @property string $encoding
 * @property string|null $name_ru
 * @property string|null $name_uz
 * @property string|null $name_en
 * @property int|null $organization
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Region query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Region whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Region whereEncoding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Region whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Region whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Region whereNameRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Region whereNameUz($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Region whereOrganization($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Region whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Region whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Region extends Model
{
    protected $fillable = [
        'encoding', 'name_ru', 'name_en', 'name_uz', 'organization', 'status'
    ];

}
