<?php

    namespace App\Http\Controllers;

    use App\Activity;
    use App\Assignment;
    use App\Client;
    use App\Organization;
    use App\Position;
    use App\Project;
    use App\Responsibilities;
    use App\User;
    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Redirect;
    use Illuminate\Support\Facades\URL;
    use test\Mockery\AllowsExpectsSyntaxTest;

    class ProfileController extends Controller
    {
        /**
         * Create a new controller instance.
         *
         * @return void
         */
        public function __construct ()
        {
            $this->middleware('auth');
        }

        public function edit_index ( Request $request )
        {
            if ( $request->id <> Auth::user()->id && Auth::user()->role !== 1 ) abort('403');
            $user = User::find($request->id);
//            $position     = Position::find($user->position);
//            $project      = Project::find($position->project);
//            $organization = Organization::find($project->organization);
            return view('pages.settings.users.profile.edit.index')->with([ 'user' => $user, 'position' => $position, 'organization' => $organization, 'project' => $project ]);
        }

        public function edit_password ( Request $request )
        {
            if ( $request->id <> Auth::user()->id && Auth::user()->role !== 1 ) abort('403');

            if ( Auth::user()->role === 1 ) {
                $user           = User::find($request->id);
                $user->password = bcrypt($request->get('new-password'));
                $user->save();

                return redirect()->back()->with("success", "Пароль успешно изменен!!!");
            }
            else {
                if ( !( Hash::check($request->get('current-password'), User::find($request->id)->password) ) ) {
                    // The passwords matches
                    return redirect()->back()->with("error", "Введенный Вами пароль не совпадает с действующим");
                }

                if ( strcmp($request->get('current-password'), $request->get('new-password')) == 0 ) {
                    //Current password and new password are same
                    return redirect()->back()->with("error", "Новый пароль не должен совпадать с старым паролем. Пожалуйста придумайте другой пароль");
                }

                $validatedData = $request->validate(
                    [
                        'current-password' => 'required',
                        'new-password'     => 'required|string|min:6|confirmed',
                    ]
                );

                //Change Password
                $user           = User::find($request->id);
                $user->password = bcrypt($request->get('new-password'));
                $user->save();

                return redirect()->back()->with("success", "Пароль успешно изменен!!!");
            }
        }

        public function activity ( Request $request )
        {
            if ( $request->project ) $project = $request->project;
            else $project = 4;

            function time2seconds ( $time )
            {
                list($hours, $mins, $secs) = explode(':', $time);
                return ( $hours * 3600 ) + ( $mins * 60 ) + $secs;
            }


            function seconds2time ( $time )
            {
                $hours = floor($time / 3600);
                $mins  = floor($time / 60 % 60);
                $secs  = floor($time % 60);
                return sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
            }

            $filter = [
                $request->startDate ? $request->startDate : '2020-03-01',
                $request->endDate ? $request->endDate : '2020-06-31'
            ];

            $user = User::where('users.id', $request->id)
                        ->where('users.status', '>', 0)
                        ->select('users.name_' . app()->getLocale() . ' as name', 'users.id as id', 'users.position')
                        ->first();

            $position = Position::leftJoin('regions', 'regions.id', '=', 'positions.region')
                ->whereIn('positions.id', $user->position)->where('positions.project', $project)
                ->select('positions.name_' . app()->getLocale() . ' as position', 'positions.id', 'regions.encoding', 'positions.project')
                ->first();

            if($user->id === 4 && $request->startDate >= '2020-05-31' && $request->project == 2) {
                $filter = [
                    $request->startDate,
                    $request->endDate
                ];
                $tempPos = 106;
            } elseif($user->id === 4 && $request->startDate <= '2020-05-31' && $request->project == 2) {
                $filter = [
                    $request->startDate,
                    $request->endDate
                ];
                $tempPos = 89;
            } else {
                $tempPos = $position->id;
            }


            $activities = Activity::leftJoin('assignments', 'assignments.id', '=', 'activities.assignment')
                                  ->leftJoin('users', 'users.id', '=', 'activities.user')
                                  ->where('activities.user', $request->id)
                                  ->where('activities.status', '>', 0)
                                  ->whereBetween('date', $filter)
                                  ->where('assignments.project', $project)
                                  ->select(
                                      \DB::raw('TIMEDIFF(`activities`.`end`, `activities`.`start`) as `diff`'),
                                      'activities.start',
                                      'activities.end',
                                      'activities.comment',
                                      'activities.status',
                                      'activities.assignment as assignment_id',
                                      'assignments.mark',
                                      'users.id as user_id',
                                      'users.name_' . app()->getLocale() . ' as user_name',
                                      'activities.id',
                                      'activities.date',
                                      'assignments.prison',
                                      'assignments.service',
                                      'assignments.type',
                                      'assignments.status as assignment_status',
                                      'assignments.id as assignment_id',
                                      \DB::raw('JSON_UNQUOTE(JSON_EXTRACT(`assignments`.`administrants`, \'$[0].' . $tempPos . '\')) as responsibility_a_id'),
                                      \DB::raw('JSON_UNQUOTE(JSON_EXTRACT(`assignments`.`helpers`, \'$[0].' . $tempPos . '\')) as responsibility_h_id'),
                                      \DB::raw('JSON_UNQUOTE(JSON_EXTRACT(`assignments`.`supervisors`, \'$[0].' . $tempPos . '\')) as responsibility_s_id')
                                  )
                                  ->orderBy('activities.date', 'asc')
                                  ->orderBy('activities.start', 'asc')
                                  ->get();

//            dd($activities);

            foreach ( $activities as $activity ) {
                if ( $activity->type == "to" ) {
                    $activitiesTripArray[$activity->date->format('Y-m-d')][$activity->type] = $activity->end;
                }

                if ( $activity->type == "of" ) {
                    $activitiesTripArray[$activity->date->format('Y-m-d')][$activity->type] = $activity->start;
                }
            }

            foreach ( $activitiesTripArray as $date => $time ) {
                $otherActivityInTrip = Activity::where('status', '>', 0)
                                               ->where('date', $date)
                                               ->where('user', $request->id)
                                               ->where('start', '>=', $time['to'])
                                               ->where('end', '<=', $time['of'])
                                               ->where('start', '<=', $time['of'])
                                               ->where('end', '>=', $time['to'])
                                               ->select(\DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(`activities`.`end`, `activities`.`start`))) as `sum`'), 'date')
                                               ->first()->sum;
                is_null($otherActivityInTrip) ? $otherActivityInTrip = 0 : $otherActivityInTrip;
                $ActivityTrip += ( time2seconds($time['of']) - time2seconds($time['to']) ) - $otherActivityInTrip;;
            }

//            dd($ActivityTrip);

            $responsibilitiesDiff[9] = seconds2time($ActivityTrip);

            $responsibilitiesDiffArray = Activity::leftJoin('assignments', 'assignments.id', '=', 'activities.assignment')
                ->where('activities.user', $request->id)
                ->where('activities.status', '>', 0)
                ->whereBetween('date', $filter)
                ->where('assignments.project', $project)
                ->select(
                    \DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(`activities`.`end`, `activities`.`start`)))) as `sum`'),
                    \DB::raw('if(JSON_UNQUOTE(JSON_EXTRACT(`assignments`.`administrants`, \'$[0].' . $tempPos . '\')),JSON_UNQUOTE(JSON_EXTRACT(`assignments`.`administrants`, \'$[0].' . $tempPos . '\')), if(JSON_UNQUOTE(JSON_EXTRACT(`assignments`.`helpers`, \'$[0].' . $tempPos . '\')),JSON_UNQUOTE(JSON_EXTRACT(`assignments`.`helpers`, \'$[0].' . $tempPos . '\')),                        if(JSON_UNQUOTE(JSON_EXTRACT(`assignments`.`supervisors`, \'$[0].' . $tempPos . '\')),JSON_UNQUOTE(JSON_EXTRACT(`assignments`.`supervisors`, \'$[0].' . $tempPos . '\')),NULL))) as responsibility_id'),
                    'assignments.prison'
                )
                ->groupBy('responsibility_id')
                ->get();

            foreach ($responsibilitiesDiffArray as $item) {
                $responsibilitiesId[]                           = $item->responsibility_id;
                $responsibilitiesDiff[$item->responsibility_id] = $item->sum;
            }


            $responsibilities = Responsibilities::where('position', 'like', '%"' . $tempPos . '"%')
                                                ->select('name_' . app()->getLocale() . ' as name', 'id', 'type')
                                                ->orderBy('type')
                                                ->get();

            foreach ( $responsibilities as $responsibility ) {
                $responsibilitiesNames[$responsibility->id] = $responsibility->name;
                $responsibility->diff                       = $responsibilitiesDiff[$responsibility->id];
                $typesDiff[$responsibility->type]           += time2seconds($responsibilitiesDiff[$responsibility->id]);
            }

            foreach ( $typesDiff as $item ) {
                $timeDiff += $item;
            }

            foreach ( $typesDiff as $key => $value ) {
                $typesDiff[$key] = seconds2time($value);
            }


            $timeSheet = Activity::leftJoin('assignments', 'assignments.id', '=', 'activities.assignment')
                                 ->whereBetween('activities.date', $filter)
                                 ->where('activities.status', '>', 0)
                                 ->where('activities.user', $request->id)
                                 ->where('assignments.project', $project)
                                 ->select(
                                     \DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(`activities`.`end`, `activities`.`start`)))) as `sum`'),
                                     \DB::raw('DAY(`activities`.`date`) as `day`')
                                 )
                                 ->groupBy('day')
                                 ->get();


            foreach ( $timeSheet as $item ) {
                $timeSheetReturn[$item->day] = $item->sum;
            }


            return view('pages.settings.users.profile.activity.index')
                ->with(
                    [
                        'user'                  => $user,
                        'activities'            => $activities,
                        'responsibilities'      => $responsibilities,
                        'timeDiff'              => seconds2time($timeDiff),
                        'typesDiff'             => $typesDiff,
                        'responsibilitiesNames' => $responsibilitiesNames,
                        'timeSheet'             => $timeSheetReturn,
                        'position'              => $position
                    ]
                );
        }

        public function activityEdit ( Request $request )
        {
            $clients = Client::all();

            $activity = Activity::leftJoin('assignments', 'assignments.id', '=', 'activities.assignment')
                                ->where('activities.id', $request->id)
                                ->select('assignments.id as assignment_id', 'activities.id', 'activities.clients', 'assignments.mark', 'activities.start', 'activities.end', 'activities.date', 'activities.comment', 'user')
                                ->first();

            $positions = Position::leftJoin('regions', 'regions.id', '=', 'positions.region')
                                 ->select('positions.*', 'regions.encoding')
                                 ->whereProject($request->project)->get();

            foreach ( $positions as $position ) {
                $user                                                            = User::where('position', 'LIKE', '%' . $position->id . '%')->where('status', '>=', 1)->first();
                $users_result[$request->project][$position->encoding][$user->id] = $position->name_ru;
            }

            $position = User::find($activity->user)->position;

            $responsibility_id = Assignment::where('id', $activity->assignment_id)
                                           ->select(\DB::raw('JSON_UNQUOTE(JSON_EXTRACT(`assignments`.`administrants`, \'$[0].' . $position . '\')) as id'))
                                           ->first();

            $responsibilities = Responsibilities::select('id', 'name_' . app()->getLocale() . ' as name')->where('position', 'like', '%"' . $position . '"%')->get();

            return view('pages.settings.users.profile.activity.edit')->with(
                [
                    'activity'         => $activity,
                    'responsibilities' => $responsibilities,
                    'rid'              => $responsibility_id,
                    'position'         => $position,
                    'users'            => $users_result,
                    'clients'          => $clients,
                    'project'          => $request->project,
                ]
            );
        }

        public function activityEditSave ( Request $request )
        {
            $activity['date']    = $request->post('date');
            $activity['start']   = $request->post('start');
            $activity['end']     = $request->post('end');
            $activity['comment'] = $request->post('comment');
            $activity['clients'] = $request->post('clients');

            $route = '';
            if ( session('startDate') && session('endDate') ) {
                $route = '&startDate=' . session('startDate') . '&endDate=' . session('endDate');
            }
            $assignmentId = Activity::find($request->id)->assignment;

            $assignment = Assignment::find($assignmentId);


            Activity::find($request->id)->update($activity);
//        \DB::statement("UPDATE `assignments` SET `administrants` = JSON_COMPACT(JSON_SET(`administrants`,'$.".$request->post('position')."',\"".$request->post('responsibility')."\")), `mark` = \"".$request->post('mark')."\" WHERE `id` = ".$request->assignment);
            return redirect(route('ActivityUser', [ 'id' => $request->post('user') ]));
        }

        public function activityDelete ( Request $request )
        {
            Activity::find($request->id)->update([ 'status' => 0 ]);
            return Redirect::to(URL::previous() . '#activity-' . $request->id);
        }

        public function activitySupervision ( Request $request )
        {
            Activity::find($request->id)->update([ 'status' => 2 ]);
            return Redirect::to(URL::previous() . '#activity-' . $request->id);
        }

        public function activityNotVerification ( Request $request )
        {
            Activity::find($request->id)->update([ 'status' => 1 ]);
            return Redirect::to(URL::previous() . '#activity-' . $request->id);
        }

        public function assignmentSupervision ( Request $request )
        {
            Assignment::find($request->id)->update([ 'status' => 2 ]);
            return Redirect::to(URL::previous() . '#activity-' . $request->id);
        }

        public function getAllActivities ( Request $request )
        {
            $activities = Activity::leftJoin('assignments', 'assignments.id', '=', 'activities.assignment')
                                  ->where('activities.user', $request->id)
                                  ->where('activities.status', '>=', 1)
                                  ->select('activities.id', 'assignments.mark', 'activities.start', 'activities.end', 'activities.date', 'activities.comment')
                                  ->get();

            $i = 0;
            foreach ( $activities as $activity ) {
                $result[$i]['id']          = $activity->id;
                $result[$i]['title']       = $activity->comment;
                $result[$i]['description'] = $activity->comment;
                $result[$i]['start']       = $activity->date->format('Y-m-d') . ' ' . $activity->start;
                $result[$i]['end']         = $activity->date->format('Y-m-d') . ' ' . $activity->end;
                $i++;
            }

            return json_encode($result);
        }

        public function activityClone ( Request $request )
        {
            $activity = Activity::leftJoin('users', 'users.id', '=', 'activities.user')
                                ->where('activities.id', $request->id)
                                ->select('users.name_' . app()->getLocale() . ' as user', 'users.id as user_id', 'activities.date', 'activities.start', 'activities.end', 'activities.comment', 'activities.id')
                                ->first();

            $users = User::leftJoin('positions', 'positions.id', '=', 'users.position')
                         ->leftJoin('regions', 'regions.id', '=', 'positions.region')
                         ->where('positions.project', 4)
                         ->select('users.name_' . app()->getLocale() . ' as name', 'users.id', 'regions.encoding as region')
                         ->orderBy('region')
                         ->orderBy('users.id')
                         ->get();

            foreach ( $users as $user ) {
                $usersReturn[$user->region][$user->id] = $user->name;
            }

            return view('pages.settings.users.profile.activity.clone')->with([ 'users' => $usersReturn, 'activity' => $activity ]);
        }
    }
