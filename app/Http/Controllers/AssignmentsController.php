<?php

    namespace App\Http\Controllers;

    use App\Assignment;
    use App\Position;
    use App\Prison;
    use App\Project;
    use App\Region;
    use App\Responsibilities;
    use App\SupportMessage;
    use App\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Maatwebsite\Excel\Excel;
    use Telegram\Bot\Laravel\Facades\Telegram;

    class AssignmentsController extends Controller
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

        public function index ()
        {
            if ( auth()->user()->role === 1 ) $projects = Project::select('id', 'encoding', 'status')->get();
            else {
                $positions = Position::whereIn('id', auth()->user()->position)->get();
                foreach ( $positions as $position ) {
                    $project_id[] = $position->project;
                }
                $projects = Project::whereIn('id', $project_id)->select('id', 'encoding', 'status')->get();
            }
            return view('pages.assignments.projects')->with([ 'projects' => $projects ]);
        }

        public function Assignments ( Request $request )
        {
            $positions = Position::whereProject($request->project)->where('status', '>=', 1)->get();

            $regions = Region::all();

            foreach ( $positions as $position ) {
                $users = User::where('users.position', 'like', '%"' . $position->id . '"%')
                             ->where([ 'users.status' => 1 ])
                             ->select('users.id', 'users.name_' . app()->getLocale() . ' as name')
                             ->get();
                foreach ( $users as $user ) {
                    if ( $user->id === auth()->user()->id ) {
                        $userThis           = $user;
                        $userThis->position = $position->id;
                    }
                    $users_result[$regions->find($position->region)->encoding][][$position->id] = $user->name;
                }
            }

            $prisons = Prison::where('status', 1)->get();

//        $projects = Project::where('beginning', '<', now())
//            ->where('end', '>', now())
//            ->where('status', 1)
//            ->get();

            $administrants = Assignment::where('administrants', 'LIKE', '%"' . $userThis->position . '":%')->where('project', $request->project)->where('author', '=', '1')->where('start', '>=', '2020-01-01')->where('status', '>=', 1)->get();
            $helpers       = Assignment::where('helpers', 'LIKE', '%"' . $userThis->position . '":%')->where('project', $request->project)->where('author', '=', '1')->where('start', '>=', '2020-01-01')->where('status', '>=', 1)->get();
            $supervisors   = Assignment::where('supervisors', 'LIKE', '%"' . $userThis->position . '":%')->where('project', $request->project)->where('author', '=', '1')->where('start', '>=', '2020-01-01')->where('status', '>=', 1)->get();
            $author        = Assignment::whereAuthor(auth()->user()->id)->where('project', $request->project)->where('start', '>=', '2020-01-01')->where('status', '>=', 1)->get();

            return view('pages.assignments.assignments')->with(
                [
                    'users'         => $users_result,
                    'prisons'       => $prisons,
                    'administrants' => $administrants,
                    'helpers'       => $helpers,
                    'supervisors'   => $supervisors,
                    'author'        => $author,
                    'project'       => $request->project
                ]
            );
        }

        public function getResponsibility ( Request $request )
        {
            $types[1] = 'Профильная деятельность';
            $types[2] = 'Административная деятельность';
            $types[3] = 'Методическая деятельность';
            $types[4] = 'Хозяйственная деятельность';
            $types[5] = 'Волонтерство';

            $responsibilities = Responsibilities::where([ 'status' => 1 ])
                                                ->where('position', 'like', '%"' . ( $request->id == '89' && auth()->user()->id == 4 ? '106' : $request->id ) . '"%')
                                                ->select('id', 'name_' . app()->getLocale() . ' as name', 'type')
                                                ->orderBy('type')
                                                ->get();

            foreach ( $responsibilities as $responsibility ) {
                $tempResponse[$responsibility->type][$responsibility->id] = $responsibility->name;
            }

            $return = '<option></option>';
            foreach ( $tempResponse as $key => $value ) {
                $return .= '<optgroup label="' . $types[$key] . '">';
                foreach ( $tempResponse[$key] as $id => $name ) {
                    $return .= '<option data-type="' . $request->type . '" value="' . $request->id . '_' . $id . '">' . $name . '</option>';
                }
                $return .= '</optgroup>';
            }

            return $return;
        }

        public function create ( Request $request )
        {
            foreach ( $request->administrants as $administrant ) {
                $explode                    = explode('_', $administrant);
                $administrants[$explode[0]] = $explode[1];
            }

            foreach ( $request->helpers as $helper ) {
                $explode              = explode('_', $helper);
                $helpers[$explode[0]] = $explode[1];
            }

            foreach ( $request->supervisors as $supervisor ) {
                $explode                  = explode('_', $supervisor);
                $supervisors[$explode[0]] = $explode[1];
            }

            $assignment = Assignment::create(array_merge($request->except('_token', 'administrants', 'helpers', 'supervisors'), [ 'author' => auth()->user()->id, 'administrants' => $administrants, 'helpers' => $helpers, 'supervisors' => $supervisors, 'status' => auth()->user()->role >= 2 ? 1 : 2 ]));

//        $text = 'Пользователь: ' . auth()->user()->name_ru . '
//Hashtag: #user' . auth()->user()->id . '
//Message:
//
//Вы создали поручение.
//'.route('assignments-get', ['id' => $assignment->id]);
//
//        Telegram::sendMessage([
//            'chat_id' => auth()->user()->telegram_id,
//            'disable_web_page_preview' => true,
//            'text' => $text
//        ]);
//
//
//
//        foreach ($request->administrants as $administrant) {
//            $text = 'Пользователь: ' . auth()->user()->name_ru . '
//Hashtag: #user' . auth()->user()->id . '
//Message:
//
//Вам назначено новое поручение.
//Статус: Исполнитель.
//'.route('assignments-get', ['id' => $assignment->id]);
//
//            Telegram::sendMessage([
//                'chat_id' => User::find($administrant)->telegram_id,
//                'disable_web_page_preview' => true,
//                'text' => $text
//            ]);
//        }
//
//
//
//        foreach ($request->helpers as $helper) {
//            $text = 'Пользователь: ' . auth()->user()->name_ru . '
//Hashtag: #user' . auth()->user()->id . '
//Message:
//
//Вам назначено новое поручение.
//Статус: Помощник.
//'.route('assignments-get', ['id' => $assignment->id]);
//
//            Telegram::sendMessage([
//                'chat_id' => User::find($helper)->telegram_id,
//                'disable_web_page_preview' => true,
//                'text' => $text
//            ]);
//        }
//
//
//
//        foreach ($request->supervisors as $supervisor) {
//            $text = 'Пользователь: ' . auth()->user()->name_ru . '
//Hashtag: #user' . auth()->user()->id . '
//Message:
//
//Вам назначено новое поручение.
//Статус: Верификатор.
//'.route('assignments-get', ['id' => $assignment->id]);
//
//            Telegram::sendMessage([
//                'chat_id' => User::find($supervisor)->telegram_id,
//                'disable_web_page_preview' => true,
//                'text' => $text
//            ]);
//        }

            return redirect()->back();
        }

        public function getAll ( Request $request )
        {
            $position = Position::whereIn('id', auth()->user()->position)->whereProject($request->project)->first();

            define(position, $position->id);

            $assignments = Assignment::where('status', '>=', 1)
                                     ->where('mark', 'NOT LIKE', '%Временное поручение (%')
                                     ->where('start', '>=', '2020-01-01')
                                     ->where('project', $request->project)
                                     ->where(
                                         function ( $query ) {
                                             $query->orWhere('author', auth()->user()->id);
                                             $query->orWhere('administrants', 'like', '%"' . position . '":%');
                                             $query->orWhere('helpers', 'like', '%"' . position . '":%');
                                             $query->orWhere('supervisors', 'like', '%"' . position . '":%');
                                         }
                                     );
            if ( position == 76 ) $assignments = $assignments->where('author', '=', auth()->user()->id)->get();
            else $assignments = $assignments->groupBy(['start','mark'])->get();


            $i      = 0;
            $result = [];
            foreach ( $assignments as $assignment ) {
                if ( auth()->user()->id === $assignment->author ) {
                    $colors = [
                        'backgroundColor' => '#c6dcff',
                        'borderColor'     => '#0062ff',
                        'textColor'       => '#000'
                    ];
                };
                if ( array_key_exists(position, $assignment->administrants) ) {
                    $colors = [
                        'backgroundColor' => '#ff9e9e',
                        'borderColor'     => '#ff0000',
                        'textColor'       => '#000'
                    ];
                };
                if ( array_key_exists(position, $assignment->helpers) ) {
                    $colors = [
                        'backgroundColor' => '#9effaa',
                        'borderColor'     => '#00b716',
                        'textColor'       => '#000'
                    ];
                };
                if ( array_key_exists(position, $assignment->supervisors) ) {
                    $colors = [
                        'backgroundColor' => '#f9f59a',
                        'borderColor'     => '#fff200',
                        'textColor'       => '#000'
                    ];
                };
                $result[$i]['id']    = $assignment->id;
                $result[$i]['title'] = $assignment->mark;
                $result[$i]['url']   = route('assignments-get', [ 'id' => $assignment->id ]);
                $result[$i]['start'] = $assignment->start->toDateTimeString();
                $result[$i]['end']   = $assignment->end->toDateTimeString();
                $result[$i]          = array_merge($result[$i], $colors);
                $i++;
            }

            return json_encode($result);
        }

        public function get ( Request $request )
        {

            $assignment = Assignment::where('assignments.status', '>=', 1)
                                    ->where([ 'assignments.id' => $request->id ])
                                    ->leftJoin('users as author', 'author.id', '=', 'assignments.author')
                                    ->leftJoin('projects', 'projects.id', '=', 'assignments.project')
                                    ->select(
                                        'assignments.*',
                                        'author.name_ru as author_name',
                                        'projects.name_ru as project_name'
                                    )
                                    ->first();

            $assignmentMain = Assignment::find($assignment->assignment);

            foreach ( (array)$assignment->administrants as $position => $responsibility ) {
                $user                       = User::where('position', 'LIKE', '%"' . $position . '"%')->first()->name_ru;
                $responsibilityName         = Responsibilities::whereId($responsibility)->first()->name_ru;
                $administrants_array[$user] = $responsibilityName;
            }
            $assignment->administrants_array = $administrants_array;

            foreach ( (array)$assignment->helpers as $position => $responsibility ) {
                $user                 = User::where('position', 'LIKE', '%"' . $position . '"%')->first()->name_ru;
                $responsibilityName   = Responsibilities::whereId($responsibility)->first()->name_ru;
                $helpers_array[$user] = $responsibilityName;
            }
            $assignment->helpers_array = $helpers_array;

            foreach ( (array)$assignment->supervisors as $position => $responsibility ) {
                $user                     = User::where('position', 'LIKE', '%"' . $position . '"%')->first()->name_ru;
                $responsibilityName       = Responsibilities::whereId($responsibility)->first()->name_ru;
                $supervisors_array[$user] = $responsibilityName;
            }
            $assignment->supervisors_array = $supervisors_array;

            if ( in_array(auth()->user()->id, (array)$assignment->administrants) ) {
                $assignment->background = 'bg-danger';
                $assignment->textColor  = 'tx-white bd-white';
                $assignment->status     = 'administrants';
            }
            if ( in_array(auth()->user()->id, (array)$assignment->helpers) ) {
                $assignment->background = 'bg-success';
                $assignment->textColor  = 'tx-white bd-white';
                $assignment->status     = 'helpers';
            }
            if ( in_array(auth()->user()->id, (array)$assignment->supervisors) ) {
                $assignment->background = 'bg-warning';
                $assignment->textColor  = 'tx-white bd-white';
                $assignment->status     = 'supervisors';
            }

            if ( $assignment->author === auth()->user()->id ) {
                $assignment->background = 'bg-info';
                $assignment->textColor  = 'tx-white bd-white';
                $assignment->status     = 'author';
            }

            return view('pages.assignments.assignment')->with([ 'assignment' => $assignment, 'assignmentMain' => $assignmentMain ]);
        }

        public function activation ( Request $request )
        {
            Assignment::whereId($request->id)->update(
                [
                    'start' => '2019-01-01 09:00:00',
                    'end'   => '2019-12-31 17:00:00',
                ]
            );

            return redirect(route('assignments-get', [ 'id' => $request->id ]));
        }

        public function edit ( Request $request )
        {
            $assignment = Assignment::where([ 'assignments.status' => 1, 'assignments.id' => $request->id ])
                                    ->leftJoin('users as author', 'author.id', '=', 'assignments.author')
                                    ->leftJoin('projects', 'projects.id', '=', 'assignments.project')
                                    ->select(
                                        'assignments.*',
                                        'author.name_ru as author_name',
                                        'projects.name_ru as project_name'
                                    )
                                    ->first();

            $users = User::leftJoin('positions', 'positions.id', '=', 'users.position')
                         ->where('positions.project', $request->project)
                         ->where('users.status', 1)
                         ->select('users.name_' . app()->getLocale() . ' as name', 'positions.id as position')
                         ->orderBy('positions.region')
                         ->get();

            foreach ( $users as $user ) {
                $responsibilities[$user->position] = Responsibilities::where('position', 'LIKE', '%"' . $user->position . '"%')->select('id', 'name_' . app()->getLocale() . ' as name')->get();
            }

            return view('pages.assignments.assignmentEdit')->with([ 'assignment' => $assignment, 'users' => $users, 'responsibilities' => $responsibilities ]);
        }

        public function editSave ( Request $request )
        {
            dd($request->post());
        }

    }
