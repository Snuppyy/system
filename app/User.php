<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Cmgmyr\Messenger\Traits\Messagable;

/**
 * App\User
 *
 * @property int $id
 * @property string|null $name_ru
 * @property string|null $name_en
 * @property string|null $name_uz
 * @property string $email
 * @property string|null $avatar
 * @property string $password
 * @property int|null $role
 * @property int|null $position
 * @property string|null $about
 * @property int $status
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Cmgmyr\Messenger\Models\Message[] $messages
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\Cmgmyr\Messenger\Models\Participant[] $participants
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read \App\Position|null $positions
 * @property-read \App\Position|null $region
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Position[] $staff
 * @property-read \Illuminate\Database\Eloquent\Collection|\Cmgmyr\Messenger\Models\Thread[] $threads
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User role($roles)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereNameRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereNameUz($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $telegram_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTelegramId($value)
 */
class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use Messagable;

    /**regions
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_ru', 'name_en', 'name_uz', 'email', 'password', 'avatart', 'role', 'position',
    ];

    protected $casts = [
        'position' => 'array'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function positions() {
        $data = Position::whereIn('id', $this->position)->get();
        foreach ( $data as $datum ) {
            $temp = Project::find($datum->project);
            $return[$temp->id] = $temp->organization;
        }
        return $return;
    }

    public function region() {
        return $this->belongsTo('App\Position', 'position')->join('regions', 'positions.region', 'regions.id');
    }

    public function project() {
        return $this->belongsTo('App\Position', 'position')->join('projects', 'positions.project', 'projects.id');
    }

    public function staff() {
        return $this->belongsToMany('App\Position', 'users', 'position', 'position')->join('regions', 'positions.region', 'regions.id');
    }

}
