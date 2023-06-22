<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Question
 *
 * @property int $id
 * @property int $author
 * @property int $id_questionnaire
 * @property int $knowledge
 * @property int $correct
 * @property string|null $name_ru
 * @property string|null $name_uz
 * @property string|null $name_en
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question whereCorrect($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question whereIdQuestionnaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question whereKnowledge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question whereNameRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question whereNameUz($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Question extends Model
{
    protected $fillable = [
        "author",
        "id_questionnaire",
        "type",
        "knowledge",
        "correct",
        "name_ru",
        "name_uz",
        "name_en",
        "status"
    ];

    protected $casts = [
        'correct' => 'array'
    ];
}
