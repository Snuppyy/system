<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Organization
 *
 * @property int $id
 * @property string|null $encoding
 * @property string|null $name_ru
 * @property string|null $name_uz
 * @property string|null $name_en
 * @property string|null $address_ru
 * @property string|null $address_uz
 * @property string|null $address_en
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Region[] $regions
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereAddressEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereAddressRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereAddressUz($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereEncoding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereNameRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereNameUz($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $author
 * @property array|null $alternate
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereAlternate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereAuthor($value)
 */
class Organization extends Model
{
    protected $fillable = [
        'name_ru', 'name_en', 'name_uz', 'address_ru', 'address_uz', 'address_en', 'status', 'author', 'alternate'
    ];

    protected $casts = [
        'alternate' => 'array',
    ];

    public function regions() {
        return $this->hasMany('App\Region', 'organization');
    }
}
