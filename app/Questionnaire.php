<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Questionnaire
 *
 * @property int $id
 * @property string $encoding
 * @property int $project
 * @property int $author
 * @property string|null $name_ru
 * @property string|null $name_uz
 * @property string|null $name_en
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Questionnaire newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Questionnaire newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Questionnaire query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Questionnaire whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Questionnaire whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Questionnaire whereEncoding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Questionnaire whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Questionnaire whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Questionnaire whereNameRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Questionnaire whereNameUz($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Questionnaire whereProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Questionnaire whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Questionnaire extends Model
{
    protected $fillable = [
        'author',
        'encoding',
        'project',
        'name_ru',
        'name_en',
        'name_uz',
    ];
}
