<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Activity
 *
 * @property int $id
 * @property int $author
 * @property int $user
 * @property int $assignment
 * @property \Illuminate\Support\Carbon $date
 * @property mixed $start
 * @property mixed $end
 * @property array|null $clients
 * @property string $comment
 * @property array|null $files
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity whereAssignment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity whereClients($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity whereFiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity whereUser($value)
 * @mixin \Eloquent
 */
class Activity extends Model
{
    protected $fillable = [
        'author', 'user', 'assignment', 'date', 'start', 'end', 'comment', 'files', 'status', 'clients'
    ];

    protected $casts = [
        'files' => 'array',
        'clients' => 'array',
        'date' => 'date',
        'start' => 'time',
        'end' => 'time',
    ];
}
