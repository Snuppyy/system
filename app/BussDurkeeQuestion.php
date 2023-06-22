<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\BussDurkeeQuestion
 *
 * @property int $id
 * @property string $question
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeQuestion whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeQuestion whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BussDurkeeQuestion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BussDurkeeQuestion extends Model
{
    protected $fillable = [
        'question',
        'status'
    ];
}
