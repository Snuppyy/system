<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Outreach
 *
 * @property int $id
 * @property int $region
 * @property string|null $encoding
 * @property string $f_name
 * @property string $s_name
 * @property \Illuminate\Support\Carbon|null $birthday
 * @property int $assistant
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Outreach newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Outreach newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Outreach query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Outreach whereAssistant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Outreach whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Outreach whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Outreach whereEncoding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Outreach whereFName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Outreach whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Outreach whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Outreach whereSName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Outreach whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Outreach whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $organization
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Outreach whereOrganization($value)
 */
class Outreach extends Model
{
    protected $fillable = [
        'f_name', 's_name', 'encoding', 'region', 'birthday', 'assistant', 'organization', 'online', 'project'
    ];

    protected $casts = [
        'birthday' => 'date',
    ];
}
