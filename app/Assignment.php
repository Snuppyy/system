<?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;

    /**
     * App\Assignment
     *
     * @property int                             $id
     * @property int                             $author
     * @property int                             $project
     * @property int|null                        $prison
     * @property int                             $service
     * @property string|null                     $type
     * @property string                          $mark
     * @property array                           $administrants
     * @property array|null                      $helpers
     * @property array|null                      $supervisors
     * @property \Illuminate\Support\Carbon      $start
     * @property \Illuminate\Support\Carbon      $end
     * @property string                          $text
     * @property array|null                      $files
     * @property int                             $status
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Assignment newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Assignment newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Assignment query()
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Assignment whereAdministrants( $value )
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Assignment whereAuthor( $value )
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Assignment whereCreatedAt( $value )
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Assignment whereEnd( $value )
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Assignment whereFiles( $value )
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Assignment whereHelpers( $value )
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Assignment whereId( $value )
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Assignment whereMark( $value )
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Assignment wherePrison( $value )
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Assignment whereProject( $value )
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Assignment whereService( $value )
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Assignment whereStart( $value )
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Assignment whereStatus( $value )
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Assignment whereSupervisors( $value )
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Assignment whereText( $value )
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Assignment whereType( $value )
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Assignment whereUpdatedAt( $value )
     * @mixin \Eloquent
     */
    class Assignment extends Model
    {
        protected $fillable
            = [
                'assignment', 'author', 'project', 'mark', 'administrants', 'helpers', 'supervisors', 'start', 'end', 'text', 'files', 'status', 'service', 'type', 'prison'
            ];

        protected $casts
            = [
                'administrants' => 'array',
                'helpers'       => 'array',
                'supervisors'   => 'array',
                'files'         => 'array',
                'start'         => 'datetime',
                'end'           => 'datetime',
            ];
    }
