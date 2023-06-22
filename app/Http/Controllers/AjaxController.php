<?php

    namespace App\Http\Controllers;

    use App\Activity;
    use App\Assignment;
    use App\Position;
    use App\ReportProject2;
    use App\Responsibilities;
    use App\User;
    use Illuminate\Http\Request;

    class AjaxController extends Controller
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

        public function activityGetAssignments ( Request $request )
        {

            $user = User::find($request->user);
            $position = Position::whereIn('id', $user->position)->where('project', $request->project)->first()->id;


            $responsibilitiesData = Responsibilities::where('position', 'LIKE', '%"' . $position . '"%')
                                                    ->select('id', 'name_' . app()->getLocale() . ' as name')->get();

            $return = '<select class="form-control select2 editable responsibility" data-position="' . $position . '">';
            $return .= '<option value="0"></option>';
            foreach ( $responsibilitiesData as $responsibility ) {
                $return .= '<option data-responsibility="' . $responsibility->name . '" data-position="' . $position . '" data-assignment-temp="' . $request->assignment . '" value="' . $responsibility->id . '">' . $responsibility->name . '</option>';
            }
            $return .= '</select>';
//
//            $return = '<select class="form-control select2 editable assignments">';
//            foreach ( $assignments as $assignment ) {
//                $return .= '<option data-responsibility="' . $responsibilities[$assignment->r_id] . '" ' . ( $assignment->id == $request->assignment ? 'selected="selected"' : '' ) . ' value="' . $assignment->id . '">' . $assignment->mark . '</option>';
//            }
//            $return .= '</select>';

            return $return;
        }

        public function activityGetUsers ( Request $request )
        {
            $assignment = Assignment::find($request->assignment);

            foreach ( $assignment->administrants as $administrant => $response ) {
                $usersData[] = $administrant;
            }

            $users = User::whereIn('position', $usersData)
                         ->select('id', 'name_' . app()->getLocale() . ' as name')
                         ->get();

            $return = '<select class="form-control select2 editable users">';
            foreach ( $users as $user ) {
                $return .= '<option ' . ( $user->id == $request->user ? 'selected="selected"' : '' ) . ' value="' . $user->id . '">' . $user->name . '</option>';
            }
            $return .= '</select>';

            return $return;
        }

        public function activitySave ( Request $request )
        {
            $administrants[$request->position] = $request->responsibility;
            $old = Assignment::find($request->assignment);
            $new = $old->replicate();
            $new->administrants = $administrants;
            $new->save();
            $newId = $new->id;
            if ( $request->id ) {
                Activity::whereId($request->id)->update(
                    [
                        'user'       => $request->user,
                        'comment'    => $request->comment,
                        'date'       => $request->date,
                        'start'      => $request->start,
                        'end'        => $request->end,
                        'assignment' => $newId,
                    ]
                );
            } else {
                return Activity::firstOrCreate(
                    [
                        'author'     => auth()->user()->id,
                        'user'       => $request->user,
                        'comment'    => $request->comment,
                        'assignment' => $newId,
                        'date'       => $request->date,
                        'start'      => $request->start,
                        'end'        => $request->end,
                    ]
                );
            }
        }

        public function reportSave ( Request $request )
        {
            ReportProject2::updateOrCreate(
                [ 'author' => auth()->user()->id, 'date' => $request->date, 'region' => $request->region ],
                [ $request->type => $request->value, 'editor' => $request->editor ]
            );
            return $request->value;
        }
    }
