<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\BussDurkeeKey
 *
 * @property int $id
 * @property string $name
 * @property array $questions_true
 * @property array $questions_false
 * @property int $coefficient
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeKey newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeKey newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeKey query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeKey whereCoefficient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeKey whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeKey whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeKey whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeKey whereQuestionsFalse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeKey whereQuestionsTrue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeKey whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeKey whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BussDurkeeKey extends Model
{
    protected $fillable = [
        'name',
        'questions_true',
        'questions_false',
        'coefficient',
        'status',
    ];

    protected $casts = [
        'questions_true' => 'array',
        'questions_false' => 'array',
    ];
}
