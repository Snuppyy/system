<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\VariantsOfAnswer
 *
 * @property int $id
 * @property int $id_question
 * @property string|null $name_ru
 * @property string|null $name_uz
 * @property string|null $name_en
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VariantsOfAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VariantsOfAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VariantsOfAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VariantsOfAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VariantsOfAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VariantsOfAnswer whereIdQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VariantsOfAnswer whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VariantsOfAnswer whereNameRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VariantsOfAnswer whereNameUz($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VariantsOfAnswer whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VariantsOfAnswer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class VariantsOfAnswer extends Model
{
    //
}
