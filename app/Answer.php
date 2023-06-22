<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Answer
 *
 * @property int $id
 * @property int $author
 * @property int $project
 * @property int|null $questionnaire
 * @property int $region
 * @property int|null $type
 * @property \Illuminate\Support\Carbon $date
 * @property int|null $outreach
 * @property string|null $volunteer
 * @property array $answers
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereAnswers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereOutreach($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereQuestionnaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereVolunteer($value)
 * @mixin \Eloquent
 * @property int|null $webinar
 * @property string|null $scan
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereScan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Answer whereWebinar($value)
 */
class Answer extends Model
{
    protected $fillable = [
        'author', 'questionnaire', 'type', 'region', 'date', 'outreach', 'volunteer', 'answers', 'status', 'scan', 'webinar', 'client'
    ];

    protected $casts = [
        'answers' => 'array',
        'date' => 'date',
    ];
}
