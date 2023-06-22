<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Position
 *
 * @property int $id
 * @property string|null $name_ru
 * @property string|null $name_uz
 * @property string|null $name_en
 * @property int|null $region
 * @property \App\User $user
 * @property array $project
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Position newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Position newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Position query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Position whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Position whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Position whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Position whereNameRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Position whereNameUz($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Position whereProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Position whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Position whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Position whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Position whereUser($value)
 * @mixin \Eloquent
 */
class Position extends Model
{
    protected $fillable = [
        'name_ru', 'name_en', 'name_uz', 'region', 'user', 'project', 'status',
    ];

    protected $casts = [
        'user' => 'array',
        'project' => 'array'
    ];

    public function user() {
        return $this->belongsTo('App\User', 'user');
    }
}
