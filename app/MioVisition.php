<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\MioVisition
 *
 * @property int $id
 * @property int $author
 * @property int $project
 * @property int $region
 * @property mixed $datetime
 * @property string|null $phone
 * @property int $user
 * @property string $name
 * @property int $type
 * @property string $address
 * @property string $coordinates
 * @property string $comments
 * @property int|null $availabilitySyringes2
 * @property int|null $procurementSyringes2
 * @property int|null $availabilitySyringes5
 * @property int|null $procurementSyringes5
 * @property int|null $availabilitySyringes10
 * @property int|null $procurementSyringes10
 * @property int|null $availabilityDoily
 * @property int|null $procurementDoily
 * @property int|null $availabilityCondomsM
 * @property int|null $procurementCondomsM
 * @property int|null $availabilityCondomsW
 * @property int|null $procurementCondomsW
 * @property int|null $availabilityHivBlood
 * @property int|null $procurementHivBlood
 * @property int|null $availabilityHivSpittle
 * @property int|null $procurementHivSpittle
 * @property array|null $files
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereAvailabilityCondomsM($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereAvailabilityCondomsW($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereAvailabilityDoily($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereAvailabilityHivBlood($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereAvailabilityHivSpittle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereAvailabilitySyringes10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereAvailabilitySyringes2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereAvailabilitySyringes5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereCoordinates($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereFiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereProcurementCondomsM($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereProcurementCondomsW($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereProcurementDoily($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereProcurementHivBlood($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereProcurementHivSpittle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereProcurementSyringes10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereProcurementSyringes2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereProcurementSyringes5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereUser($value)
 * @mixin \Eloquent
 * @property string|null $scan
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MioVisition whereScan($value)
 */
class MioVisition extends Model
{
    protected $fillable = [
        'author',
        'region',
        'datetime',
        'phone',
        'user',
        'name',
        'type',
        'address',
        'coordinates',
        'comments',
        'availabilitySyringes2',
        'procurementSyringes2',
        'availabilitySyringes5',
        'procurementSyringes5',
        'availabilitySyringes10',
        'procurementSyringes10',
        'availabilityDoily',
        'procurementDoily',
        'availabilityCondomsM',
        'procurementCondomsM',
        'availabilityCondomsW',
        'procurementCondomsW',
        'availabilityHivBlood',
        'procurementHivBlood',
        'availabilityHivSpittle',
        'procurementHivSpittle',
        'files',
        'scan',
        'status',
    ];

    protected $casts = [
        'datetime' => 'datetime:Y-m-d',
        'files' => 'array',
    ];
}
