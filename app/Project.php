<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Project
 *
 * @property int $id
 * @property string|null $name_ru
 * @property string|null $name_uz
 * @property string|null $name_en
 * @property int|null $organization
 * @property string $beginning
 * @property string $end
 * @property int $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereBeginning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereNameRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereNameUz($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereOrganization($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project query()
 * @property string $encoding
 * @property int $author
 * @property string|null $alternate
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereAlternate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereEncoding($value)
 */
class Project extends Model
{
    protected $fillable = [
        'name_ru', 'name_en', 'name_uz', 'organization', 'beginning', 'end', 'status',
    ];

    protected $casts = [
        'user' => 'array',
        'project' => 'array'
    ];
}
