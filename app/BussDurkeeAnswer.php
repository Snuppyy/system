<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\BussDurkeeAnswer
 *
 * @property int $id
 * @property int $author
 * @property string $name
 * @property array $answers
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeAnswer whereAnswers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeAnswer whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeAnswer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeAnswer whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeAnswer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BussDurkeeAnswer extends Model
{
    protected $fillable = [
        'author',
        'client',
        'type',
        'answers',
        'status',
    ];

    protected $casts = [
        'answers' => 'array'
    ];
}
