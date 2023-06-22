<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Assignment;
use App\Client;
use App\Exports\VerificationExport;
use App\ExportTempExcel;
use App\Position;
use App\Prison;
use App\Project;
use App\Region;
use App\Responsibilities;
use App\User;
use Carbon\Carbon;
use Carbon\Traits\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class ActivityStatisticsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $countUsers = Client::all()->count();
        $countPositions = Position::all()->count();
        if (auth()->user()->role === 1) $projects = Project::select('id', 'encoding', 'status')->get();
        else {
            $positions = Position::whereIn('id', auth()->user()->position)->get();
            foreach ($positions as $position) {
                $project_id[] = $position->project;
            }
            $projects = Project::whereIn('id', $project_id)->select('id', 'encoding', 'status')->get();
        }
        return view('pages.statistics.activities')->with(['projects' => $projects, 'countUsers' => $countUsers, 'countPositions' => $countPositions]);
    }

    public function users(Request $request)
    {
        $usersName = User::all();
        if ($request->project === 'all') $positionsData = Position::all();
        else $positionsData = Position::where('project', $request->project)->get();
        foreach ($positionsData as $position) {
            $positions[$position->id] = $position->name_ru . ' - ' . Project::find($position->project)->encoding;
        }

        $projects = Project::where('status', 1)->get();

        return view('pages.statistics.activitiesUsers')->with(['users' => $usersName, 'positions' => $positions, 'projects' => $projects]);
    }

    public function user(Request $request)
    {
        function time2seconds($time)
        {
            list($hours, $mins, $secs) = explode(':', $time);
            return ($hours * 3600) + ($mins * 60) + $secs;
        }

        function seconds2time($time)
        {
            $hours = floor($time / 3600);
            $mins = floor($time / 60 % 60);
            $secs = floor($time % 60);
            return sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
        }


        if($request->id == 132 || $request->id == 62) {
            $userID = [132, 62];
        } else {
            $userID = [$request->id];
        }

        $filter = [
            $request->startDate ? $request->startDate : Carbon::now()->startOfMonth()->format('Y-m-d'),
            $request->endDate ? $request->endDate : Carbon::now()->endOfMonth()->format('Y-m-d')
        ];

        $user = User::where('users.id', $request->id)
            ->where('users.status', '>', 0)
            ->select('users.name_' . app()->getLocale() . ' as name', 'users.id as id', 'users.position')
            ->first();

        $positions = Position::whereIn('id', $user->position)->get();
        $projectsData = Project::all();

        foreach ($projectsData as $project) {
            $projects[$project->id] = $project->encoding;
        }

        $activities = Activity::leftJoin('assignments', 'assignments.id', '=', 'activities.assignment')
            ->leftJoin('users', 'users.id', '=', 'activities.user')
            ->whereIn('activities.user', $userID)
            ->where('activities.status', '>', 0)
            ->whereBetween('date', $filter)
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
                'assignments.project'
            )
            ->orderBy('activities.date', 'asc')
            ->orderBy('activities.start', 'asc')
            ->get();

        $diff = Activity::leftJoin('users', 'users.id', '=', 'activities.user')
            ->whereIn('activities.user', $userID)
            ->where('activities.status', '>', 0)
            ->whereBetween('date', $filter)
            ->select(
                \DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(`activities`.`end`, `activities`.`start`)))) as `sum`'),
                'activities.status as id'
            )
            ->groupBy(['activities.status'])
            ->get();

        $responsibilitiesDiffAllDayArray = Activity::leftJoin('assignments', 'assignments.id', '=', 'activities.assignment')
            ->whereIn('activities.user', $userID)
            ->where('activities.status', '>', 0)
            ->whereBetween('date', $filter)
            ->select(
                \DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(`activities`.`end`, `activities`.`start`)))) as `sum`'),
                'activities.date as date'
            )
            ->groupBy([\DB::raw('DAY(`date`)')])
            ->get();

        foreach ($responsibilitiesDiffAllDayArray as $item) {
            $activityAllDayDiff['month'] = $item->date->format('n');
            $activityAllDayDiff['year'] = $item->date->format('Y');
            $activityAllDayDiff[$item->date->format('j')] = $item->sum;
        }

        foreach ($positions as $position) {
            $responsibilitiesDiffArray[$position->project] = Activity::leftJoin('assignments', 'assignments.id', '=', 'activities.assignment')
                ->whereIn('activities.user', $userID)
                ->where('activities.status', '>', 0)
                ->whereBetween('date', $filter)
                ->where('assignments.project', $position->project)
                ->select(
                    \DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(`activities`.`end`, `activities`.`start`)))) as `sum`'),
                    \DB::raw('if(JSON_UNQUOTE(JSON_EXTRACT(`assignments`.`administrants`, \'$[0].' . $position->id . '\')),JSON_UNQUOTE(JSON_EXTRACT(`assignments`.`administrants`, \'$[0].' . $position->id . '\')), if(JSON_UNQUOTE(JSON_EXTRACT(`assignments`.`helpers`, \'$[0].' . $position->id . '\')),JSON_UNQUOTE(JSON_EXTRACT(`assignments`.`helpers`, \'$[0].' . $position->id . '\')), if(JSON_UNQUOTE(JSON_EXTRACT(`assignments`.`supervisors`, \'$[0].' . $position->id . '\')),JSON_UNQUOTE(JSON_EXTRACT(`assignments`.`supervisors`, \'$[0].' . $position->id . '\')),NULL))) as responsibility_id'),
                    'assignments.prison'
                )
                ->groupBy('responsibility_id')
                ->get();


            $responsibilitiesForList = Activity::leftJoin('assignments', 'assignments.id', '=', 'activities.assignment')
                ->whereIn('activities.user', $userID)
                ->where('activities.status', '>', 0)
                ->whereBetween('date', $filter)
                ->where('assignments.project', $position->project)
                ->select(
                    'activities.id',
                    \DB::raw('if(JSON_UNQUOTE(JSON_EXTRACT(`assignments`.`administrants`, \'$[0].' . $position->id . '\')),JSON_UNQUOTE(JSON_EXTRACT(`assignments`.`administrants`, \'$[0].' . $position->id . '\')), if(JSON_UNQUOTE(JSON_EXTRACT(`assignments`.`helpers`, \'$[0].' . $position->id . '\')),JSON_UNQUOTE(JSON_EXTRACT(`assignments`.`helpers`, \'$[0].' . $position->id . '\')), if(JSON_UNQUOTE(JSON_EXTRACT(`assignments`.`supervisors`, \'$[0].' . $position->id . '\')),JSON_UNQUOTE(JSON_EXTRACT(`assignments`.`supervisors`, \'$[0].' . $position->id . '\')),NULL))) as responsibility_id')
                )
                ->get();


            foreach ($responsibilitiesForList as $item) {
                $dataResponsibilities[$item->id] = $item->responsibility_id;
            }


            $responsibilitiesDiffDayArray[$position->project] = Activity::leftJoin('assignments', 'assignments.id', '=', 'activities.assignment')
                ->whereIn('activities.user', $userID)
                ->where('activities.status', '>', 0)
                ->whereBetween('date', $filter)
                ->where('assignments.project', $position->project)
                ->select(
                    \DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(`activities`.`end`, `activities`.`start`)))) as `sum`'),
                    'activities.date as date'
                )
                ->groupBy([\DB::raw('DAY(`date`)')])
                ->get();

            foreach ($responsibilitiesDiffArray[$position->project] as $item) {
                $responsibilitiesId[$position->project][] = $item->responsibility_id;
                $responsibilitiesDiff[$position->project][$item->responsibility_id] = $item->sum;
            }

            foreach ($responsibilitiesDiffDayArray[$position->project] as $item) {
                $activityDayDiff[$position->project]['month'] = $item->date->format('n');
                $activityDayDiff[$position->project]['year'] = $item->date->format('Y');
                $activityDayDiff[$position->project][$item->date->format('j')] = $item->sum;
            }

            $responsibilities[$position->project] = Responsibilities::where('position', 'like', '%"' . $position->id . '"%')
                ->where('status', '>=', '1')
                ->select('name_' . app()->getLocale() . ' as name', 'id', 'type')
                ->orderBy('type')
                ->get();

            foreach ($responsibilities[$position->project] as $responsibility) {
                $responsibilitiesNames[$position->project][$responsibility->id] = $responsibility->name;
                $responsibility->diff = $responsibilitiesDiff[$position->project][$responsibility->id];
                $typesDiff[$position->project][$responsibility->type] += time2seconds($responsibilitiesDiff[$position->project][$responsibility->id]);
            }

            foreach ($typesDiff[$position->project] as $item) {
                $timeDiff[$position->project] += $item;
            }

            foreach ($typesDiff[$position->project] as $key => $value) {
                $typesDiff[$position->project][$key] = seconds2time($value);
            }
        }

//        if (\Auth::user()->role === 1) dd($dataResponsibilities);


        return view('pages.statistics.activityUser')
            ->with(
                [
                    'user' => $user,
                    'activities' => $activities,
                    'projects' => $projects,
                    'positions' => $positions,
                    'diff' => $diff,
                    'typesDiff' => $typesDiff,
                    'responsibilitiesNames' => $responsibilitiesNames,
                    'responsibilities' => $responsibilities,
                    'filter' => $filter,
                    'dayDiff' => $activityDayDiff,
                    'dayAllDiff' => $activityAllDayDiff,
                    'dataResponsibilities' => $dataResponsibilities
                ]
            );
    }

    public function project(Request $request)
    {
        $filter = [
            $request->startDate ? $request->startDate : '2020-03-01',
            $request->endDate ? $request->endDate : '2020-12-31'
        ];

        $staff = Position::leftJoin('regions', 'regions.id', '=', 'positions.region')
            ->whereProject($request->project)
            ->select('positions.id', 'positions.region', 'regions.encoding', 'regions.name_' . app()->getLocale() . ' as regionName', 'positions.name_' . app()->getLocale() . ' as positionName')
            ->get();
        foreach ($staff as $item) {
            $users = User::orWhere('position', 'LIKE', '%"' . $item->id . '"%')
                ->get();

            foreach ($users as $user) {
                $usersId[] = $user->id;
                $userInfo[$item->region][$user->id]['encoding'] = $item->encoding;
                $userInfo[$item->region][$user->id]['region'] = $item->regionName;
                $userInfo[$item->region][$user->id]['position'] = $item->positionName;
                $userInfo[$item->region][$user->id]['user'] = $user->name_ru;
            }
        }

        $activitiesUsers = Activity::leftJoin('users', 'users.id', '=', 'activities.user')
            ->leftJoin('assignments', 'assignments.id', '=', 'activities.assignment')
            ->select(
                'users.name_' . app()->getLocale() . ' as name',
                'users.id as id',
                'users.status as status',
                \DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(`activities`.`end`, `activities`.`start`)))) as `sum`')
            )
            ->where('activities.status', '>', 0)
            ->whereIn('activities.user', $usersId)
            ->whereBetween('date', $filter)
            ->where('assignments.project', $request->project)
            ->groupBy('activities.user')
            ->orderBy('users.id')
            ->get();

        return view('pages.statistics.activity')->with(
            [
                'project' => $request->project,
                'activitiesUsers' => $activitiesUsers,
                'usersInfo' => $userInfo,

            ]
        );
    }

    public function clients(Request $request)
    {

        $indicators[0]['name'] = "Групповое информирование по вопросам туберкулеза";
        $indicators[1]['name'] = "Индивидуальное информирование по вопросам туберкулеза";
        $indicators[2]['name'] = "Групповые консультации ТБ по социальным вопросам";
        $indicators[3]['name'] = "Индивидуальные консультации ТБ по социальным вопросам";
        $indicators[4]['name'] = "Групповые занятия ТБ по психологическим вопросам";
        $indicators[5]['name'] = "Индивидуальные консультации ТБ по психологическим вопросам";
        $indicators[6]['name'] = "Групповые консультации ТБ по юридическим вопросам";
        $indicators[7]['name'] = "Индивидуальные консультации ТБ по юридическим вопросам";

        $indicators[0]['name_o'] = "Групповое информирование для освободившихся по вопросам туберкулеза в офисе";
        $indicators[1]['name_o'] = "Индивидуальное информирование для освободившихся по вопросам туберкулеза в офисе";
        $indicators[2]['name_o'] = "Групповые консультации для освободившихся по социальным вопросам в офисе";
        $indicators[3]['name_o'] = "Консультации для освободившихся по социальным вопросам в офисе";
        $indicators[4]['name_o'] = "Групповые занятия для освободившихся по психологическим вопросам в офисе";
        $indicators[5]['name_o'] = "Индивидуальные консультации для освободившихся по психологическим вопросам в офисе";
        $indicators[6]['name_o'] = "Групповые консультации для освободившихся по юридическим вопросам в офисе";
        $indicators[7]['name_o'] = "Индивидуальные консультации для освободившихся по юридическим вопросам в офисе";

        $indicators[0]['responsibility'] = 3;
        $indicators[1]['responsibility'] = 3;
        $indicators[2]['responsibility'] = 19;
        $indicators[3]['responsibility'] = 18;
        $indicators[4]['responsibility'] = 2;
        $indicators[5]['responsibility'] = 1;
        $indicators[6]['responsibility'] = 17;
        $indicators[7]['responsibility'] = 16;


        $indicators[0]['type'] = 'multiple';
        $indicators[1]['type'] = 'once';
        $indicators[2]['type'] = 'multiple';
        $indicators[3]['type'] = 'once';
        $indicators[4]['type'] = 'multiple';
        $indicators[5]['type'] = 'once';
        $indicators[6]['type'] = 'multiple';
        $indicators[7]['type'] = 'once';

        $filter = [
            '2019-10-01',
            '2019-10-31'
        ];

        $activitiesClients = Activity::leftJoin('assignments', 'assignments.id', '=', 'activities.assignment')
            ->leftJoin('users', 'users.id', '=', 'activities.user')
            ->where('activities.status', '>', 0)
            ->whereBetween('date', $filter)
            ->whereNotNull('activities.clients')
            ->select('activities.clients', 'assignments.administrants', 'activities.date')
            ->get();

        foreach ($activitiesClients as $activitiesClient) {
            foreach ($indicators as $key => $indicator) {
                if (strripos($activitiesClient->administrants, ":\"" . $indicator['responsibility'] . "\"")) {
                    if (count($activitiesClient->clients) > 1 && $indicators[$key]['type'] === 'multiple') {
                        foreach ($activitiesClient->clients as $client) {
                            $indicators[$key]['all']['clients'][$client] = $activitiesClient->date->format('Y-m-d');
                            $indicators[$key]['all']['actions'][$activitiesClient->date->format('Y-m-d')] = $activitiesClient->date->format('Y-m-d');
                        }
                    } else if (count($activitiesClient->clients) === 1 && $indicators[$key]['type'] === 'once') {
                        foreach ($activitiesClient->clients as $client) {
                            $indicators[$key]['all']['clients'][$client] = $activitiesClient->date->format('Y-m-d');
                            $indicators[$key]['all']['actions'][$client] = $activitiesClient->date->format('Y-m-d');
                        }
                    }
                }
            }
        }

        $clients = Client::select('id', 'region', 'prison')->get();
        foreach ($clients as $client) {
            $clientsData[$client->id][$client->prison] = $client->region;
        }

        foreach ($indicators as $key => $indicator) {
            foreach ($indicators[$key]['all']['clients'] as $client => $date) {
                $clientData = $clientsData[$client];
                $region = $clientData[key($clientData)];
                $prison = key($clientData);

                $indicatorsTest[$key][11][$prison][$region]['clients'][$client] = $date;
                if ($indicators[$key]['type'] === 'once') {
                    $indicatorsTest[$key][11][$prison][$region]['actions'][$client] = $date;
                } else {
                    $indicatorsTest[$key][11][$prison][$region]['actions'][$date] = $date;
                }
            }
            $indicators[$key][11][$prison][$region]['actions'] = count($indicatorsTest[$key][11][$prison][$region]['actions']);
            $indicators[$key][11][$prison][$region]['clients'] = count($indicatorsTest[$key][11][$prison][$region]['clients']);
        }

        $indicators[0]['01'][1][11]['actions'] = 0;
        $indicators[0]['01'][1][11]['clients'] = 0;
        $indicators[1]['01'][1][11]['actions'] = 4;
        $indicators[1]['01'][1][11]['clients'] = 4;
        $indicators[2]['01'][1][11]['actions'] = 0;
        $indicators[2]['01'][1][11]['clients'] = 0;
        $indicators[3]['01'][1][11]['actions'] = 6;
        $indicators[3]['01'][1][11]['clients'] = 6;
        $indicators[4]['01'][1][11]['actions'] = 2;
        $indicators[4]['01'][1][11]['clients'] = 6;
        $indicators[5]['01'][1][11]['actions'] = 6;
        $indicators[5]['01'][1][11]['clients'] = 4;
        $indicators[6]['01'][1][11]['actions'] = 0;
        $indicators[6]['01'][1][11]['clients'] = 0;
        $indicators[7]['01'][1][11]['actions'] = 4;
        $indicators[7]['01'][1][11]['clients'] = 4;

        $indicators[0]['10'][1][11]['actions'] = 3;
        $indicators[0]['10'][1][11]['clients'] = 29;
        $indicators[1]['10'][1][11]['actions'] = 6;
        $indicators[1]['10'][1][11]['clients'] = 5;
        $indicators[2]['10'][1][11]['actions'] = 0;
        $indicators[2]['10'][1][11]['clients'] = 0;
        $indicators[3]['10'][1][11]['actions'] = 0;
        $indicators[3]['10'][1][11]['clients'] = 0;
        $indicators[4]['10'][1][11]['actions'] = 3;
        $indicators[4]['10'][1][11]['clients'] = 5;
        $indicators[5]['10'][1][11]['actions'] = 3;
        $indicators[5]['10'][1][11]['clients'] = 3;
        $indicators[6]['10'][1][11]['actions'] = 2;
        $indicators[6]['10'][1][11]['clients'] = 3;
        $indicators[7]['10'][1][11]['actions'] = 2;
        $indicators[7]['10'][1][11]['clients'] = 2;

        $indicators[0]['11'][1][11]['actions'] = 1;
        $indicators[0]['11'][1][11]['clients'] = 13;
        $indicators[1]['11'][1][11]['actions'] = 11;
        $indicators[1]['11'][1][11]['clients'] = 11;
        $indicators[2]['11'][1][11]['actions'] = 1;
        $indicators[2]['11'][1][11]['clients'] = 4;
        $indicators[3]['11'][1][11]['actions'] = 2;
        $indicators[3]['11'][1][11]['clients'] = 2;
        $indicators[4]['11'][1][11]['actions'] = 2;
        $indicators[4]['11'][1][11]['clients'] = 6;
        $indicators[5]['11'][1][11]['actions'] = 0;
        $indicators[5]['11'][1][11]['clients'] = 0;
        $indicators[6]['11'][1][11]['actions'] = 2;
        $indicators[6]['11'][1][11]['clients'] = 4;
        $indicators[7]['11'][1][11]['actions'] = 0;
        $indicators[7]['11'][1][11]['clients'] = 0;

        $indicators[0]['12'][1][11]['actions'] = 1;
        $indicators[0]['12'][1][11]['clients'] = 17;
        $indicators[1]['12'][1][11]['actions'] = 1;
        $indicators[1]['12'][1][11]['clients'] = 1;
        $indicators[2]['12'][1][11]['actions'] = 0;
        $indicators[2]['12'][1][11]['clients'] = 0;
        $indicators[3]['12'][1][11]['actions'] = 4;
        $indicators[3]['12'][1][11]['clients'] = 4;
        $indicators[4]['12'][1][11]['actions'] = 2;
        $indicators[4]['12'][1][11]['clients'] = 5;
        $indicators[5]['12'][1][11]['actions'] = 2;
        $indicators[5]['12'][1][11]['clients'] = 2;
        $indicators[6]['12'][1][11]['actions'] = 1;
        $indicators[6]['12'][1][11]['clients'] = 4;
        $indicators[7]['12'][1][11]['actions'] = 0;
        $indicators[7]['12'][1][11]['clients'] = 0;

        $indicators[0]['02'][1][11]['actions'] = 3;
        $indicators[0]['02'][1][11]['clients'] = 60;
        $indicators[1]['02'][1][11]['actions'] = 4;
        $indicators[1]['02'][1][11]['clients'] = 4;
        $indicators[2]['02'][1][11]['actions'] = 0;
        $indicators[2]['02'][1][11]['clients'] = 0;
        $indicators[3]['02'][1][11]['actions'] = 2;
        $indicators[3]['02'][1][11]['clients'] = 1;
        $indicators[4]['02'][1][11]['actions'] = 3;
        $indicators[4]['02'][1][11]['clients'] = 6;
        $indicators[5]['02'][1][11]['actions'] = 5;
        $indicators[5]['02'][1][11]['clients'] = 5;
        $indicators[6]['02'][1][11]['actions'] = 0;
        $indicators[6]['02'][1][11]['clients'] = 0;
        $indicators[7]['02'][1][11]['actions'] = 5;
        $indicators[7]['02'][1][11]['clients'] = 5;

        $indicators[0]['02'][3][3]['actions'] = 1;
        $indicators[0]['02'][3][3]['clients'] = 68;
        $indicators[1]['02'][3][3]['actions'] = 4;
        $indicators[1]['02'][3][3]['clients'] = 4;
        $indicators[2]['02'][3][3]['actions'] = 0;
        $indicators[2]['02'][3][3]['clients'] = 0;
        $indicators[3]['02'][3][3]['actions'] = 7;
        $indicators[3]['02'][3][3]['clients'] = 7;
        $indicators[4]['02'][3][3]['actions'] = 1;
        $indicators[4]['02'][3][3]['clients'] = 50;
        $indicators[5]['02'][3][3]['actions'] = 3;
        $indicators[5]['02'][3][3]['clients'] = 3;
        $indicators[6]['02'][3][3]['actions'] = 0;
        $indicators[6]['02'][3][3]['clients'] = 0;
        $indicators[7]['02'][3][3]['actions'] = 2;
        $indicators[7]['02'][3][3]['clients'] = 2;

        $indicators[0]['10'][3][3]['actions'] = 4;
        $indicators[0]['10'][3][3]['clients'] = 49;
        $indicators[1]['10'][3][3]['actions'] = 26;
        $indicators[1]['10'][3][3]['clients'] = 26;
        $indicators[2]['10'][3][3]['actions'] = 0;
        $indicators[2]['10'][3][3]['clients'] = 0;
        $indicators[3]['10'][3][3]['actions'] = 5;
        $indicators[3]['10'][3][3]['clients'] = 5;
        $indicators[4]['10'][3][3]['actions'] = 4;
        $indicators[4]['10'][3][3]['clients'] = 35;
        $indicators[5]['10'][3][3]['actions'] = 10;
        $indicators[5]['10'][3][3]['clients'] = 10;
        $indicators[6]['10'][3][3]['actions'] = 4;
        $indicators[6]['10'][3][3]['clients'] = 49;
        $indicators[7]['10'][3][3]['actions'] = 11;
        $indicators[7]['10'][3][3]['clients'] = 11;

        $indicators[0]['11'][3][3]['actions'] = 4;
        $indicators[0]['11'][3][3]['clients'] = 51;
        $indicators[1]['11'][3][3]['actions'] = 21;
        $indicators[1]['11'][3][3]['clients'] = 21;
        $indicators[2]['11'][3][3]['actions'] = 0;
        $indicators[2]['11'][3][3]['clients'] = 0;
        $indicators[3]['11'][3][3]['actions'] = 5;
        $indicators[3]['11'][3][3]['clients'] = 5;
        $indicators[4]['11'][3][3]['actions'] = 4;
        $indicators[4]['11'][3][3]['clients'] = 40;
        $indicators[5]['11'][3][3]['actions'] = 9;
        $indicators[5]['11'][3][3]['clients'] = 9;
        $indicators[6]['11'][3][3]['actions'] = 4;
        $indicators[6]['11'][3][3]['clients'] = 51;
        $indicators[7]['11'][3][3]['actions'] = 11;
        $indicators[7]['11'][3][3]['clients'] = 11;

        $indicators[0]['12'][3][3]['actions'] = 2;
        $indicators[0]['12'][3][3]['clients'] = 25;
        $indicators[1]['12'][3][3]['actions'] = 12;
        $indicators[1]['12'][3][3]['clients'] = 12;
        $indicators[2]['12'][3][3]['actions'] = 0;
        $indicators[2]['12'][3][3]['clients'] = 0;
        $indicators[3]['12'][3][3]['actions'] = 5;
        $indicators[3]['12'][3][3]['clients'] = 5;
        $indicators[4]['12'][3][3]['actions'] = 2;
        $indicators[4]['12'][3][3]['clients'] = 20;
        $indicators[5]['12'][3][3]['actions'] = 6;
        $indicators[5]['12'][3][3]['clients'] = 6;
        $indicators[6]['12'][3][3]['actions'] = 2;
        $indicators[6]['12'][3][3]['clients'] = 25;
        $indicators[7]['12'][3][3]['actions'] = 6;
        $indicators[7]['12'][3][3]['clients'] = 6;


        $indicators[0]['03'][1][11]['actions'] = 1;
        $indicators[0]['03'][1][11]['clients'] = 50;
        $indicators[1]['03'][1][11]['actions'] = 0;
        $indicators[1]['03'][1][11]['clients'] = 0;
        $indicators[2]['03'][1][11]['actions'] = 0;
        $indicators[2]['03'][1][11]['clients'] = 0;
        $indicators[3]['03'][1][11]['actions'] = 1;
        $indicators[3]['03'][1][11]['clients'] = 1;
        $indicators[4]['03'][1][11]['actions'] = 2;
        $indicators[4]['03'][1][11]['clients'] = 6;
        $indicators[5]['03'][1][11]['actions'] = 2;
        $indicators[5]['03'][1][11]['clients'] = 2;
        $indicators[6]['03'][1][11]['actions'] = 1;
        $indicators[6]['03'][1][11]['clients'] = 5;
        $indicators[7]['03'][1][11]['actions'] = 1;
        $indicators[7]['03'][1][11]['clients'] = 1;

        $indicators[0]['03'][3][3]['actions'] = 3;
        $indicators[0]['03'][3][3]['clients'] = 62;
        $indicators[1]['03'][3][3]['actions'] = 4;
        $indicators[1]['03'][3][3]['clients'] = 4;
        $indicators[2]['03'][3][3]['actions'] = 0;
        $indicators[2]['03'][3][3]['clients'] = 0;
        $indicators[3]['03'][3][3]['actions'] = 20;
        $indicators[3]['03'][3][3]['clients'] = 20;
        $indicators[4]['03'][3][3]['actions'] = 1;
        $indicators[4]['03'][3][3]['clients'] = 50;
        $indicators[5]['03'][3][3]['actions'] = 10;
        $indicators[5]['03'][3][3]['clients'] = 10;
        $indicators[6]['03'][3][3]['actions'] = 3;
        $indicators[6]['03'][3][3]['clients'] = 62;
        $indicators[7]['03'][3][3]['actions'] = 7;
        $indicators[7]['03'][3][3]['clients'] = 7;

        $indicators[0]['03'][2][1]['actions'] = 1;
        $indicators[0]['03'][2][1]['clients'] = 45;
        $indicators[1]['03'][2][1]['actions'] = 0;
        $indicators[1]['03'][2][1]['clients'] = 0;
        $indicators[2]['03'][2][1]['actions'] = 0;
        $indicators[2]['03'][2][1]['clients'] = 0;
        $indicators[3]['03'][2][1]['actions'] = 3;
        $indicators[3]['03'][2][1]['clients'] = 3;
        $indicators[4]['03'][2][1]['actions'] = 0;
        $indicators[4]['03'][2][1]['clients'] = 0;
        $indicators[5]['03'][2][1]['actions'] = 3;
        $indicators[5]['03'][2][1]['clients'] = 3;
        $indicators[6]['03'][2][1]['actions'] = 0;
        $indicators[6]['03'][2][1]['clients'] = 0;
        $indicators[7]['03'][2][1]['actions'] = 3;
        $indicators[7]['03'][2][1]['clients'] = 3;

        $indicators[0]['10'][2][1]['actions'] = 1;
        $indicators[0]['10'][2][1]['clients'] = 12;
        $indicators[1]['10'][2][1]['actions'] = 10;
        $indicators[1]['10'][2][1]['clients'] = 10;
        $indicators[2]['10'][2][1]['actions'] = 0;
        $indicators[2]['10'][2][1]['clients'] = 0;
        $indicators[3]['10'][2][1]['actions'] = 13;
        $indicators[3]['10'][2][1]['clients'] = 13;
        $indicators[4]['10'][2][1]['actions'] = 4;
        $indicators[4]['10'][2][1]['clients'] = 40;
        $indicators[5]['10'][2][1]['actions'] = 0;
        $indicators[5]['10'][2][1]['clients'] = 0;
        $indicators[6]['10'][2][1]['actions'] = 0;
        $indicators[6]['10'][2][1]['clients'] = 0;
        $indicators[7]['10'][2][1]['actions'] = 11;
        $indicators[7]['10'][2][1]['clients'] = 11;

        $indicators[0]['11'][2][1]['actions'] = 1;
        $indicators[0]['11'][2][1]['clients'] = 10;
        $indicators[1]['11'][2][1]['actions'] = 19;
        $indicators[1]['11'][2][1]['clients'] = 18;
        $indicators[2]['11'][2][1]['actions'] = 0;
        $indicators[2]['11'][2][1]['clients'] = 0;
        $indicators[3]['11'][2][1]['actions'] = 14;
        $indicators[3]['11'][2][1]['clients'] = 13;
        $indicators[4]['11'][2][1]['actions'] = 3;
        $indicators[4]['11'][2][1]['clients'] = 29;
        $indicators[5]['11'][2][1]['actions'] = 0;
        $indicators[5]['11'][2][1]['clients'] = 0;
        $indicators[6]['11'][2][1]['actions'] = 0;
        $indicators[6]['11'][2][1]['clients'] = 0;
        $indicators[7]['11'][2][1]['actions'] = 12;
        $indicators[7]['11'][2][1]['clients'] = 12;

        $indicators[0]['12'][2][1]['actions'] = 0;
        $indicators[0]['12'][2][1]['clients'] = 0;
        $indicators[1]['12'][2][1]['actions'] = 9;
        $indicators[1]['12'][2][1]['clients'] = 8;
        $indicators[2]['12'][2][1]['actions'] = 0;
        $indicators[2]['12'][2][1]['clients'] = 0;
        $indicators[3]['12'][2][1]['actions'] = 0;
        $indicators[3]['12'][2][1]['clients'] = 0;
        $indicators[4]['12'][2][1]['actions'] = 0;
        $indicators[4]['12'][2][1]['clients'] = 0;
        $indicators[5]['12'][2][1]['actions'] = 0;
        $indicators[5]['12'][2][1]['clients'] = 0;
        $indicators[6]['12'][2][1]['actions'] = 0;
        $indicators[6]['12'][2][1]['clients'] = 0;
        $indicators[7]['12'][2][1]['actions'] = 0;
        $indicators[7]['12'][2][1]['clients'] = 0;

        $indicators[0]['04'][1][11]['actions'] = 1;
        $indicators[0]['04'][1][11]['clients'] = 17;
        $indicators[1]['04'][1][11]['actions'] = 0;
        $indicators[1]['04'][1][11]['clients'] = 0;
        $indicators[2]['04'][1][11]['actions'] = 1;
        $indicators[2]['04'][1][11]['clients'] = 4;
        $indicators[3]['04'][1][11]['actions'] = 0;
        $indicators[3]['04'][1][11]['clients'] = 0;
        $indicators[4]['04'][1][11]['actions'] = 3;
        $indicators[4]['04'][1][11]['clients'] = 5;
        $indicators[5]['04'][1][11]['actions'] = 2;
        $indicators[5]['04'][1][11]['clients'] = 2;
        $indicators[6]['04'][1][11]['actions'] = 2;
        $indicators[6]['04'][1][11]['clients'] = 4;
        $indicators[7]['04'][1][11]['actions'] = 0;
        $indicators[7]['04'][1][11]['clients'] = 0;

        $indicators[0]['04'][3][3]['actions'] = 4;
        $indicators[0]['04'][3][3]['clients'] = 90;
        $indicators[1]['04'][3][3]['actions'] = 10;
        $indicators[1]['04'][3][3]['clients'] = 10;
        $indicators[2]['04'][3][3]['actions'] = 0;
        $indicators[2]['04'][3][3]['clients'] = 0;
        $indicators[3]['04'][3][3]['actions'] = 32;
        $indicators[3]['04'][3][3]['clients'] = 32;
        $indicators[4]['04'][3][3]['actions'] = 2;
        $indicators[4]['04'][3][3]['clients'] = 23;
        $indicators[5]['04'][3][3]['actions'] = 8;
        $indicators[5]['04'][3][3]['clients'] = 8;
        $indicators[6]['04'][3][3]['actions'] = 4;
        $indicators[6]['04'][3][3]['clients'] = 90;
        $indicators[7]['04'][3][3]['actions'] = 5;
        $indicators[7]['04'][3][3]['clients'] = 5;

        $indicators[0]['04'][2][1]['actions'] = 1;
        $indicators[0]['04'][2][1]['clients'] = 34;
        $indicators[1]['04'][2][1]['actions'] = 5;
        $indicators[1]['04'][2][1]['clients'] = 5;
        $indicators[2]['04'][2][1]['actions'] = 0;
        $indicators[2]['04'][2][1]['clients'] = 0;
        $indicators[3]['04'][2][1]['actions'] = 5;
        $indicators[3]['04'][2][1]['clients'] = 5;
        $indicators[4]['04'][2][1]['actions'] = 1;
        $indicators[4]['04'][2][1]['clients'] = 34;
        $indicators[5]['04'][2][1]['actions'] = 0;
        $indicators[5]['04'][2][1]['clients'] = 0;
        $indicators[6]['04'][2][1]['actions'] = 0;
        $indicators[6]['04'][2][1]['clients'] = 0;
        $indicators[7]['04'][2][1]['actions'] = 7;
        $indicators[7]['04'][2][1]['clients'] = 7;

//            $indicators[0]['10'][2][1]['actions'] = "ERROR";
//            $indicators[0]['10'][2][1]['clients'] = "ERROR";
//            $indicators[1]['10'][2][1]['actions'] = "ERROR";
//            $indicators[1]['10'][2][1]['clients'] = "ERROR";
//            $indicators[2]['10'][2][1]['actions'] = "ERROR";
//            $indicators[2]['10'][2][1]['clients'] = "ERROR";
//            $indicators[3]['10'][2][1]['actions'] = "ERROR";
//            $indicators[3]['10'][2][1]['clients'] = "ERROR";
//            $indicators[4]['10'][2][1]['actions'] = "ERROR";
//            $indicators[4]['10'][2][1]['clients'] = "ERROR";
//            $indicators[5]['10'][2][1]['actions'] = "ERROR";
//            $indicators[5]['10'][2][1]['clients'] = "ERROR";
//            $indicators[6]['10'][2][1]['actions'] = "ERROR";
//            $indicators[6]['10'][2][1]['clients'] = "ERROR";
//            $indicators[7]['10'][2][1]['actions'] = "ERROR";
//            $indicators[7]['10'][2][1]['clients'] = "ERROR";

        $indicators[0]['05'][1][11]['actions'] = 0;
        $indicators[0]['05'][1][11]['clients'] = 0;
        $indicators[1]['05'][1][11]['actions'] = 0;
        $indicators[1]['05'][1][11]['clients'] = 0;
        $indicators[2]['05'][1][11]['actions'] = 0;
        $indicators[2]['05'][1][11]['clients'] = 0;
        $indicators[3]['05'][1][11]['actions'] = 2;
        $indicators[3]['05'][1][11]['clients'] = 2;
        $indicators[4]['05'][1][11]['actions'] = 3;
        $indicators[4]['05'][1][11]['clients'] = 4;
        $indicators[5]['05'][1][11]['actions'] = 5;
        $indicators[5]['05'][1][11]['clients'] = 5;
        $indicators[6]['05'][1][11]['actions'] = 0;
        $indicators[6]['05'][1][11]['clients'] = 0;
        $indicators[7]['05'][1][11]['actions'] = 4;
        $indicators[7]['05'][1][11]['clients'] = 4;

        $indicators[0]['05'][3][3]['actions'] = 4;
        $indicators[0]['05'][3][3]['clients'] = 92;
        $indicators[1]['05'][3][3]['actions'] = 4;
        $indicators[1]['05'][3][3]['clients'] = 41;
        $indicators[2]['05'][3][3]['actions'] = 0;
        $indicators[2]['05'][3][3]['clients'] = 0;
        $indicators[3]['05'][3][3]['actions'] = 16;
        $indicators[3]['05'][3][3]['clients'] = 16;
        $indicators[4]['05'][3][3]['actions'] = 2;
        $indicators[4]['05'][3][3]['clients'] = 16;
        $indicators[5]['05'][3][3]['actions'] = 10;
        $indicators[5]['05'][3][3]['clients'] = 10;
        $indicators[6]['05'][3][3]['actions'] = 4;
        $indicators[6]['05'][3][3]['clients'] = 92;
        $indicators[7]['05'][3][3]['actions'] = 15;
        $indicators[7]['05'][3][3]['clients'] = 15;

        $indicators[0]['05'][2][1]['actions'] = 0;
        $indicators[0]['05'][2][1]['clients'] = 0;
        $indicators[1]['05'][2][1]['actions'] = 13;
        $indicators[1]['05'][2][1]['clients'] = 13;
        $indicators[2]['05'][2][1]['actions'] = 0;
        $indicators[2]['05'][2][1]['clients'] = 0;
        $indicators[3]['05'][2][1]['actions'] = 13;
        $indicators[3]['05'][2][1]['clients'] = 13;
        $indicators[4]['05'][2][1]['actions'] = 3;
        $indicators[4]['05'][2][1]['clients'] = 53;
        $indicators[5]['05'][2][1]['actions'] = 0;
        $indicators[5]['05'][2][1]['clients'] = 0;
        $indicators[6]['05'][2][1]['actions'] = 0;
        $indicators[6]['05'][2][1]['clients'] = 0;
        $indicators[7]['05'][2][1]['actions'] = 30;
        $indicators[7]['05'][2][1]['clients'] = 28;

        $indicators[0]['06'][1][11]['actions'] = 1;
        $indicators[0]['06'][1][11]['clients'] = 6;
        $indicators[1]['06'][1][11]['actions'] = 6;
        $indicators[1]['06'][1][11]['clients'] = 6;
        $indicators[2]['06'][1][11]['actions'] = 1;
        $indicators[2]['06'][1][11]['clients'] = 6;
        $indicators[3]['06'][1][11]['actions'] = 0;
        $indicators[3]['06'][1][11]['clients'] = 0;
        $indicators[4]['06'][1][11]['actions'] = 1;
        $indicators[4]['06'][1][11]['clients'] = 5;
        $indicators[5]['06'][1][11]['actions'] = 0;
        $indicators[5]['06'][1][11]['clients'] = 0;
        $indicators[6]['06'][1][11]['actions'] = 0;
        $indicators[6]['06'][1][11]['clients'] = 0;
        $indicators[7]['06'][1][11]['actions'] = 6;
        $indicators[7]['06'][1][11]['clients'] = 6;

        $indicators[0]['06'][3][3]['actions'] = 4;
        $indicators[0]['06'][3][3]['clients'] = 72;
        $indicators[1]['06'][3][3]['actions'] = 19;
        $indicators[1]['06'][3][3]['clients'] = 19;
        $indicators[2]['06'][3][3]['actions'] = 0;
        $indicators[2]['06'][3][3]['clients'] = 0;
        $indicators[3]['06'][3][3]['actions'] = 14;
        $indicators[3]['06'][3][3]['clients'] = 14;
        $indicators[4]['06'][3][3]['actions'] = 4;
        $indicators[4]['06'][3][3]['clients'] = 32;
        $indicators[5]['06'][3][3]['actions'] = 9;
        $indicators[5]['06'][3][3]['clients'] = 9;
        $indicators[6]['06'][3][3]['actions'] = 4;
        $indicators[6]['06'][3][3]['clients'] = 72;
        $indicators[7]['06'][3][3]['actions'] = 12;
        $indicators[7]['06'][3][3]['clients'] = 12;

        $indicators[0]['06'][2][1]['actions'] = 1;
        $indicators[0]['06'][2][1]['clients'] = 13;
        $indicators[1]['06'][2][1]['actions'] = 12;
        $indicators[1]['06'][2][1]['clients'] = 12;
        $indicators[2]['06'][2][1]['actions'] = 0;
        $indicators[2]['06'][2][1]['clients'] = 0;
        $indicators[3]['06'][2][1]['actions'] = 12;
        $indicators[3]['06'][2][1]['clients'] = 12;
        $indicators[4]['06'][2][1]['actions'] = 3;
        $indicators[4]['06'][2][1]['clients'] = 34;
        $indicators[5]['06'][2][1]['actions'] = 1;
        $indicators[5]['06'][2][1]['clients'] = 1;
        $indicators[6]['06'][2][1]['actions'] = 1;
        $indicators[6]['06'][2][1]['clients'] = 10;
        $indicators[7]['06'][2][1]['actions'] = 20;
        $indicators[7]['06'][2][1]['clients'] = 19;

        $indicators[0]['07'][1][11]['actions'] = 1;
        $indicators[0]['07'][1][11]['clients'] = 5;
        $indicators[1]['07'][1][11]['actions'] = 0;
        $indicators[1]['07'][1][11]['clients'] = 0;
        $indicators[2]['07'][1][11]['actions'] = 0;
        $indicators[2]['07'][1][11]['clients'] = 0;
        $indicators[3]['07'][1][11]['actions'] = 0;
        $indicators[3]['07'][1][11]['clients'] = 0;
        $indicators[4]['07'][1][11]['actions'] = 3;
        $indicators[4]['07'][1][11]['clients'] = 5;
        $indicators[5]['07'][1][11]['actions'] = 0;
        $indicators[5]['07'][1][11]['clients'] = 0;
        $indicators[6]['07'][1][11]['actions'] = 4;
        $indicators[6]['07'][1][11]['clients'] = 5;
        $indicators[7]['07'][1][11]['actions'] = 0;
        $indicators[7]['07'][1][11]['clients'] = 0;

        $indicators[0]['07'][3][3]['actions'] = 4;
        $indicators[0]['07'][3][3]['clients'] = 77;
        $indicators[1]['07'][3][3]['actions'] = 23;
        $indicators[1]['07'][3][3]['clients'] = 23;
        $indicators[2]['07'][3][3]['actions'] = 0;
        $indicators[2]['07'][3][3]['clients'] = 0;
        $indicators[3]['07'][3][3]['actions'] = 10;
        $indicators[3]['07'][3][3]['clients'] = 10;
        $indicators[4]['07'][3][3]['actions'] = 4;
        $indicators[4]['07'][3][3]['clients'] = 41;
        $indicators[5]['07'][3][3]['actions'] = 11;
        $indicators[5]['07'][3][3]['clients'] = 11;
        $indicators[6]['07'][3][3]['actions'] = 2;
        $indicators[6]['07'][3][3]['clients'] = 20;
        $indicators[7]['07'][3][3]['actions'] = 4;
        $indicators[7]['07'][3][3]['clients'] = 4;

        $indicators[0]['07'][2][1]['actions'] = 1;
        $indicators[0]['07'][2][1]['clients'] = 13;
        $indicators[1]['07'][2][1]['actions'] = 9;
        $indicators[1]['07'][2][1]['clients'] = 9;
        $indicators[2]['07'][2][1]['actions'] = 0;
        $indicators[2]['07'][2][1]['clients'] = 0;
        $indicators[3]['07'][2][1]['actions'] = 7;
        $indicators[3]['07'][2][1]['clients'] = 7;
        $indicators[4]['07'][2][1]['actions'] = 4;
        $indicators[4]['07'][2][1]['clients'] = 28;
        $indicators[5]['07'][2][1]['actions'] = 0;
        $indicators[5]['07'][2][1]['clients'] = 0;
        $indicators[6]['07'][2][1]['actions'] = 0;
        $indicators[6]['07'][2][1]['clients'] = 0;
        $indicators[7]['07'][2][1]['actions'] = 16;
        $indicators[7]['07'][2][1]['clients'] = 11;

        $indicators[0]['08'][1][11]['actions'] = 1;
        $indicators[0]['08'][1][11]['clients'] = 10;
        $indicators[1]['08'][1][11]['actions'] = 0;
        $indicators[1]['08'][1][11]['clients'] = 0;
        $indicators[2]['08'][1][11]['actions'] = 0;
        $indicators[2]['08'][1][11]['clients'] = 0;
        $indicators[3]['08'][1][11]['actions'] = 4;
        $indicators[3]['08'][1][11]['clients'] = 4;
        $indicators[4]['08'][1][11]['actions'] = 3;
        $indicators[4]['08'][1][11]['clients'] = 5;
        $indicators[5]['08'][1][11]['actions'] = 0;
        $indicators[5]['08'][1][11]['clients'] = 0;
        $indicators[6]['08'][1][11]['actions'] = 0;
        $indicators[6]['08'][1][11]['clients'] = 0;
        $indicators[7]['08'][1][11]['actions'] = 4;
        $indicators[7]['08'][1][11]['clients'] = 4;

        $indicators[0]['08'][3][3]['actions'] = 4;
        $indicators[0]['08'][3][3]['clients'] = 59;
        $indicators[1]['08'][3][3]['actions'] = 34;
        $indicators[1]['08'][3][3]['clients'] = 34;
        $indicators[2]['08'][3][3]['actions'] = 0;
        $indicators[2]['08'][3][3]['clients'] = 0;
        $indicators[3]['08'][3][3]['actions'] = 10;
        $indicators[3]['08'][3][3]['clients'] = 10;
        $indicators[4]['08'][3][3]['actions'] = 4;
        $indicators[4]['08'][3][3]['clients'] = 35;
        $indicators[5]['08'][3][3]['actions'] = 10;
        $indicators[5]['08'][3][3]['clients'] = 10;
        $indicators[6]['08'][3][3]['actions'] = 4;
        $indicators[6]['08'][3][3]['clients'] = 59;
        $indicators[7]['08'][3][3]['actions'] = 14;
        $indicators[7]['08'][3][3]['clients'] = 14;

        $indicators[0]['08'][2][1]['actions'] = 0;
        $indicators[0]['08'][2][1]['clients'] = 0;
        $indicators[1]['08'][2][1]['actions'] = 12;
        $indicators[1]['08'][2][1]['clients'] = 12;
        $indicators[2]['08'][2][1]['actions'] = 0;
        $indicators[2]['08'][2][1]['clients'] = 0;
        $indicators[3]['08'][2][1]['actions'] = 8;
        $indicators[3]['08'][2][1]['clients'] = 8;
        $indicators[4]['08'][2][1]['actions'] = 3;
        $indicators[4]['08'][2][1]['clients'] = 37;
        $indicators[5]['08'][2][1]['actions'] = 0;
        $indicators[5]['08'][2][1]['clients'] = 0;
        $indicators[6]['08'][2][1]['actions'] = 0;
        $indicators[6]['08'][2][1]['clients'] = 0;
        $indicators[7]['08'][2][1]['actions'] = 0;
        $indicators[7]['08'][2][1]['clients'] = 0;

        $indicators[0]['09'][1][11]['actions'] = 2;
        $indicators[0]['09'][1][11]['clients'] = 68;
        $indicators[1]['09'][1][11]['actions'] = 6;
        $indicators[1]['09'][1][11]['clients'] = 6;
        $indicators[2]['09'][1][11]['actions'] = 1;
        $indicators[2]['09'][1][11]['clients'] = 5;
        $indicators[3]['09'][1][11]['actions'] = 4;
        $indicators[3]['09'][1][11]['clients'] = 4;
        $indicators[4]['09'][1][11]['actions'] = 2;
        $indicators[4]['09'][1][11]['clients'] = 5;
        $indicators[5]['09'][1][11]['actions'] = 0;
        $indicators[5]['09'][1][11]['clients'] = 0;
        $indicators[6]['09'][1][11]['actions'] = 0;
        $indicators[6]['09'][1][11]['clients'] = 0;
        $indicators[7]['09'][1][11]['actions'] = 4;
        $indicators[7]['09'][1][11]['clients'] = 4;

        $indicators[0]['09'][3][3]['actions'] = 4;
        $indicators[0]['09'][3][3]['clients'] = 55;
        $indicators[1]['09'][3][3]['actions'] = 30;
        $indicators[1]['09'][3][3]['clients'] = 30;
        $indicators[2]['09'][3][3]['actions'] = 0;
        $indicators[2]['09'][3][3]['clients'] = 0;
        $indicators[3]['09'][3][3]['actions'] = 3;
        $indicators[3]['09'][3][3]['clients'] = 3;
        $indicators[4]['09'][3][3]['actions'] = 4;
        $indicators[4]['09'][3][3]['clients'] = 25;
        $indicators[5]['09'][3][3]['actions'] = 10;
        $indicators[5]['09'][3][3]['clients'] = 10;
        $indicators[6]['09'][3][3]['actions'] = 4;
        $indicators[6]['09'][3][3]['clients'] = 55;
        $indicators[7]['09'][3][3]['actions'] = 15;
        $indicators[7]['09'][3][3]['clients'] = 15;

        $indicators[0]['09'][2][1]['actions'] = 2;
        $indicators[0]['09'][2][1]['clients'] = 31;
        $indicators[1]['09'][2][1]['actions'] = 4;
        $indicators[1]['09'][2][1]['clients'] = 4;
        $indicators[2]['09'][2][1]['actions'] = 0;
        $indicators[2]['09'][2][1]['clients'] = 0;
        $indicators[3]['09'][2][1]['actions'] = 3;
        $indicators[3]['09'][2][1]['clients'] = 3;
        $indicators[4]['09'][2][1]['actions'] = 1;
        $indicators[4]['09'][2][1]['clients'] = 5;
        $indicators[5]['09'][2][1]['actions'] = 1;
        $indicators[5]['09'][2][1]['clients'] = 1;
        $indicators[6]['09'][2][1]['actions'] = 0;
        $indicators[6]['09'][2][1]['clients'] = 0;
        $indicators[7]['09'][2][1]['actions'] = 12;
        $indicators[7]['09'][2][1]['clients'] = 12;

        $indicators[0]['quarter3'][1][11]['actions'] = 4;
        $indicators[0]['quarter3'][1][11]['clients'] = 83;
        $indicators[1]['quarter3'][1][11]['actions'] = 6;
        $indicators[1]['quarter3'][1][11]['clients'] = 6;
        $indicators[2]['quarter3'][1][11]['actions'] = 1;
        $indicators[2]['quarter3'][1][11]['clients'] = 5;
        $indicators[3]['quarter3'][1][11]['actions'] = 8;
        $indicators[3]['quarter3'][1][11]['clients'] = 5;
        $indicators[4]['quarter3'][1][11]['actions'] = 8;
        $indicators[4]['quarter3'][1][11]['clients'] = 5;
        $indicators[5]['quarter3'][1][11]['actions'] = 0;
        $indicators[5]['quarter3'][1][11]['clients'] = 0;
        $indicators[6]['quarter3'][1][11]['actions'] = 4;
        $indicators[6]['quarter3'][1][11]['clients'] = 5;
        $indicators[7]['quarter3'][1][11]['actions'] = 8;
        $indicators[7]['quarter3'][1][11]['clients'] = 4;

        $indicators[0]['quarter3'][3][3]['actions'] = 12;
        $indicators[0]['quarter3'][3][3]['clients'] = 191;
        $indicators[1]['quarter3'][3][3]['actions'] = 87;
        $indicators[1]['quarter3'][3][3]['clients'] = 87;
        $indicators[2]['quarter3'][3][3]['actions'] = 0;
        $indicators[2]['quarter3'][3][3]['clients'] = 0;
        $indicators[3]['quarter3'][3][3]['actions'] = 23;
        $indicators[3]['quarter3'][3][3]['clients'] = 23;
        $indicators[4]['quarter3'][3][3]['actions'] = 12;
        $indicators[4]['quarter3'][3][3]['clients'] = 101;
        $indicators[5]['quarter3'][3][3]['actions'] = 31;
        $indicators[5]['quarter3'][3][3]['clients'] = 30;
        $indicators[6]['quarter3'][3][3]['actions'] = 10;
        $indicators[6]['quarter3'][3][3]['clients'] = 134;
        $indicators[7]['quarter3'][3][3]['actions'] = 33;
        $indicators[7]['quarter3'][3][3]['clients'] = 33;

        $indicators[0]['quarter3'][2][1]['actions'] = 3;
        $indicators[0]['quarter3'][2][1]['clients'] = 44;
        $indicators[1]['quarter3'][2][1]['actions'] = 25;
        $indicators[1]['quarter3'][2][1]['clients'] = 25;
        $indicators[2]['quarter3'][2][1]['actions'] = 0;
        $indicators[2]['quarter3'][2][1]['clients'] = 0;
        $indicators[3]['quarter3'][2][1]['actions'] = 18;
        $indicators[3]['quarter3'][2][1]['clients'] = 18;
        $indicators[4]['quarter3'][2][1]['actions'] = 8;
        $indicators[4]['quarter3'][2][1]['clients'] = 64;
        $indicators[5]['quarter3'][2][1]['actions'] = 1;
        $indicators[5]['quarter3'][2][1]['clients'] = 1;
        $indicators[6]['quarter3'][2][1]['actions'] = 0;
        $indicators[6]['quarter3'][2][1]['clients'] = 0;
        $indicators[7]['quarter3'][2][1]['actions'] = 28;
        $indicators[7]['quarter3'][2][1]['clients'] = 23;

        $indicators[0]['quarter3'][1][11]['actions'] = 4;
        $indicators[0]['quarter3'][1][11]['clients'] = 83;
        $indicators[1]['quarter3'][1][11]['actions'] = 6;
        $indicators[1]['quarter3'][1][11]['clients'] = 6;
        $indicators[2]['quarter3'][1][11]['actions'] = 1;
        $indicators[2]['quarter3'][1][11]['clients'] = 5;
        $indicators[3]['quarter3'][1][11]['actions'] = 8;
        $indicators[3]['quarter3'][1][11]['clients'] = 5;
        $indicators[4]['quarter3'][1][11]['actions'] = 8;
        $indicators[4]['quarter3'][1][11]['clients'] = 5;
        $indicators[5]['quarter3'][1][11]['actions'] = 0;
        $indicators[5]['quarter3'][1][11]['clients'] = 0;
        $indicators[6]['quarter3'][1][11]['actions'] = 4;
        $indicators[6]['quarter3'][1][11]['clients'] = 5;
        $indicators[7]['quarter3'][1][11]['actions'] = 8;
        $indicators[7]['quarter3'][1][11]['clients'] = 4;

        $indicators[0]['quarter3'][3][3]['actions'] = 12;
        $indicators[0]['quarter3'][3][3]['clients'] = 191;
        $indicators[1]['quarter3'][3][3]['actions'] = 87;
        $indicators[1]['quarter3'][3][3]['clients'] = 87;
        $indicators[2]['quarter3'][3][3]['actions'] = 0;
        $indicators[2]['quarter3'][3][3]['clients'] = 0;
        $indicators[3]['quarter3'][3][3]['actions'] = 23;
        $indicators[3]['quarter3'][3][3]['clients'] = 23;
        $indicators[4]['quarter3'][3][3]['actions'] = 12;
        $indicators[4]['quarter3'][3][3]['clients'] = 101;
        $indicators[5]['quarter3'][3][3]['actions'] = 31;
        $indicators[5]['quarter3'][3][3]['clients'] = 30;
        $indicators[6]['quarter3'][3][3]['actions'] = 10;
        $indicators[6]['quarter3'][3][3]['clients'] = 134;
        $indicators[7]['quarter3'][3][3]['actions'] = 33;
        $indicators[7]['quarter3'][3][3]['clients'] = 33;

        $indicators[0]['quarter3'][2][1]['actions'] = 3;
        $indicators[0]['quarter3'][2][1]['clients'] = 44;
        $indicators[1]['quarter3'][2][1]['actions'] = 25;
        $indicators[1]['quarter3'][2][1]['clients'] = 25;
        $indicators[2]['quarter3'][2][1]['actions'] = 0;
        $indicators[2]['quarter3'][2][1]['clients'] = 0;
        $indicators[3]['quarter3'][2][1]['actions'] = 18;
        $indicators[3]['quarter3'][2][1]['clients'] = 18;
        $indicators[4]['quarter3'][2][1]['actions'] = 8;
        $indicators[4]['quarter3'][2][1]['clients'] = 64;
        $indicators[5]['quarter3'][2][1]['actions'] = 1;
        $indicators[5]['quarter3'][2][1]['clients'] = 1;
        $indicators[6]['quarter3'][2][1]['actions'] = 0;
        $indicators[6]['quarter3'][2][1]['clients'] = 0;
        $indicators[7]['quarter3'][2][1]['actions'] = 28;
        $indicators[7]['quarter3'][2][1]['clients'] = 23;

        $indicators[0]['quarter4'][1][11]['actions'] = 5;
        $indicators[0]['quarter4'][1][11]['clients'] = 34;
        $indicators[1]['quarter4'][1][11]['actions'] = 17;
        $indicators[1]['quarter4'][1][11]['clients'] = 16;
        $indicators[2]['quarter4'][1][11]['actions'] = 0;
        $indicators[2]['quarter4'][1][11]['clients'] = 0;
        $indicators[3]['quarter4'][1][11]['actions'] = 6;
        $indicators[3]['quarter4'][1][11]['clients'] = 6;
        $indicators[4]['quarter4'][1][11]['actions'] = 7;
        $indicators[4]['quarter4'][1][11]['clients'] = 6;
        $indicators[5]['quarter4'][1][11]['actions'] = 5;
        $indicators[5]['quarter4'][1][11]['clients'] = 5;
        $indicators[6]['quarter4'][1][11]['actions'] = 5;
        $indicators[6]['quarter4'][1][11]['clients'] = 5;
        $indicators[7]['quarter4'][1][11]['actions'] = 2;
        $indicators[7]['quarter4'][1][11]['clients'] = 2;

        $indicators[0]['quarter4'][3][3]['actions'] = 10;
        $indicators[0]['quarter4'][3][3]['clients'] = 111;
        $indicators[1]['quarter4'][3][3]['actions'] = 59;
        $indicators[1]['quarter4'][3][3]['clients'] = 59;
        $indicators[2]['quarter4'][3][3]['actions'] = 0;
        $indicators[2]['quarter4'][3][3]['clients'] = 0;
        $indicators[3]['quarter4'][3][3]['actions'] = 15;
        $indicators[3]['quarter4'][3][3]['clients'] = 15;
        $indicators[4]['quarter4'][3][3]['actions'] = 10;
        $indicators[4]['quarter4'][3][3]['clients'] = 84;
        $indicators[5]['quarter4'][3][3]['actions'] = 25;
        $indicators[5]['quarter4'][3][3]['clients'] = 25;
        $indicators[6]['quarter4'][3][3]['actions'] = 10;
        $indicators[6]['quarter4'][3][3]['clients'] = 111;
        $indicators[7]['quarter4'][3][3]['actions'] = 28;
        $indicators[7]['quarter4'][3][3]['clients'] = 28;

        $indicators[0]['quarter4'][2][1]['actions'] = 2;
        $indicators[0]['quarter4'][2][1]['clients'] = 22;
        $indicators[1]['quarter4'][2][1]['actions'] = 38;
        $indicators[1]['quarter4'][2][1]['clients'] = 36;
        $indicators[2]['quarter4'][2][1]['actions'] = 0;
        $indicators[2]['quarter4'][2][1]['clients'] = 0;
        $indicators[3]['quarter4'][2][1]['actions'] = 27;
        $indicators[3]['quarter4'][2][1]['clients'] = 26;
        $indicators[4]['quarter4'][2][1]['actions'] = 7;
        $indicators[4]['quarter4'][2][1]['clients'] = 69;
        $indicators[5]['quarter4'][2][1]['actions'] = 0;
        $indicators[5]['quarter4'][2][1]['clients'] = 0;
        $indicators[6]['quarter4'][2][1]['actions'] = 0;
        $indicators[6]['quarter4'][2][1]['clients'] = 0;
        $indicators[7]['quarter4'][2][1]['actions'] = 23;
        $indicators[7]['quarter4'][2][1]['clients'] = 23;

        $indicators[0]['quarter1'][2][1]['actions'] = 1;
        $indicators[0]['quarter1'][2][1]['clients'] = 45;
        $indicators[1]['quarter1'][2][1]['actions'] = 0;
        $indicators[1]['quarter1'][2][1]['clients'] = 0;
        $indicators[2]['quarter1'][2][1]['actions'] = 0;
        $indicators[2]['quarter1'][2][1]['clients'] = 0;
        $indicators[3]['quarter1'][2][1]['actions'] = 3;
        $indicators[3]['quarter1'][2][1]['clients'] = 3;
        $indicators[4]['quarter1'][2][1]['actions'] = 0;
        $indicators[4]['quarter1'][2][1]['clients'] = 0;
        $indicators[5]['quarter1'][2][1]['actions'] = 3;
        $indicators[5]['quarter1'][2][1]['clients'] = 3;
        $indicators[6]['quarter1'][2][1]['actions'] = 0;
        $indicators[6]['quarter1'][2][1]['clients'] = 0;
        $indicators[7]['quarter1'][2][1]['actions'] = 3;
        $indicators[7]['quarter1'][2][1]['clients'] = 3;

        $indicators[0]['quarter1'][1][11]['actions'] = 4;
        $indicators[0]['quarter1'][1][11]['clients'] = 110;
        $indicators[1]['quarter1'][1][11]['actions'] = 4;
        $indicators[1]['quarter1'][1][11]['clients'] = 4;
        $indicators[2]['quarter1'][1][11]['actions'] = 0;
        $indicators[2]['quarter1'][1][11]['clients'] = 0;
        $indicators[3]['quarter1'][1][11]['actions'] = 7;
        $indicators[3]['quarter1'][1][11]['clients'] = 7;
        $indicators[4]['quarter1'][1][11]['actions'] = 7;
        $indicators[4]['quarter1'][1][11]['clients'] = 7;
        $indicators[5]['quarter1'][1][11]['actions'] = 13;
        $indicators[5]['quarter1'][1][11]['clients'] = 7;
        $indicators[6]['quarter1'][1][11]['actions'] = 1;
        $indicators[6]['quarter1'][1][11]['clients'] = 5;
        $indicators[7]['quarter1'][1][11]['actions'] = 10;
        $indicators[7]['quarter1'][1][11]['clients'] = 7;

        $indicators[0]['quarter1'][3][3]['actions'] = 4;
        $indicators[0]['quarter1'][3][3]['clients'] = 77;
        $indicators[1]['quarter1'][3][3]['actions'] = 8;
        $indicators[1]['quarter1'][3][3]['clients'] = 8;
        $indicators[2]['quarter1'][3][3]['actions'] = 0;
        $indicators[2]['quarter1'][3][3]['clients'] = 0;
        $indicators[3]['quarter1'][3][3]['actions'] = 27;
        $indicators[3]['quarter1'][3][3]['clients'] = 27;
        $indicators[4]['quarter1'][3][3]['actions'] = 2;
        $indicators[4]['quarter1'][3][3]['clients'] = 70;
        $indicators[5]['quarter1'][3][3]['actions'] = 13;
        $indicators[5]['quarter1'][3][3]['clients'] = 13;
        $indicators[6]['quarter1'][3][3]['actions'] = 4;
        $indicators[6]['quarter1'][3][3]['clients'] = 77;
        $indicators[7]['quarter1'][3][3]['actions'] = 9;
        $indicators[7]['quarter1'][3][3]['clients'] = 9;

        $indicators[0]['quarter2'][2][1]['actions'] = 6;
        $indicators[0]['quarter2'][2][1]['clients'] = 62;
        $indicators[1]['quarter2'][2][1]['actions'] = 25;
        $indicators[1]['quarter2'][2][1]['clients'] = 25;
        $indicators[2]['quarter2'][2][1]['actions'] = 0;
        $indicators[2]['quarter2'][2][1]['clients'] = 0;
        $indicators[3]['quarter2'][2][1]['actions'] = 30;
        $indicators[3]['quarter2'][2][1]['clients'] = 28;
        $indicators[4]['quarter2'][2][1]['actions'] = 7;
        $indicators[4]['quarter2'][2][1]['clients'] = 88;
        $indicators[5]['quarter2'][2][1]['actions'] = 1;
        $indicators[5]['quarter2'][2][1]['clients'] = 1;
        $indicators[6]['quarter2'][2][1]['actions'] = 1;
        $indicators[6]['quarter2'][2][1]['clients'] = 6;
        $indicators[7]['quarter2'][2][1]['actions'] = 57;
        $indicators[7]['quarter2'][2][1]['clients'] = 57;

        $indicators[0]['quarter2'][1][11]['actions'] = 2;
        $indicators[0]['quarter2'][1][11]['clients'] = 7;
        $indicators[1]['quarter2'][1][11]['actions'] = 6;
        $indicators[1]['quarter2'][1][11]['clients'] = 6;
        $indicators[2]['quarter2'][1][11]['actions'] = 2;
        $indicators[2]['quarter2'][1][11]['clients'] = 3;
        $indicators[3]['quarter2'][1][11]['actions'] = 2;
        $indicators[3]['quarter2'][1][11]['clients'] = 2;
        $indicators[4]['quarter2'][1][11]['actions'] = 7;
        $indicators[4]['quarter2'][1][11]['clients'] = 10;
        $indicators[5]['quarter2'][1][11]['actions'] = 7;
        $indicators[5]['quarter2'][1][11]['clients'] = 7;
        $indicators[6]['quarter2'][1][11]['actions'] = 2;
        $indicators[6]['quarter2'][1][11]['clients'] = 2;
        $indicators[7]['quarter2'][1][11]['actions'] = 10;
        $indicators[7]['quarter2'][1][11]['clients'] = 8;

        $indicators[0]['quarter2'][3][3]['actions'] = 12;
        $indicators[0]['quarter2'][3][3]['clients'] = 153;
        $indicators[1]['quarter2'][3][3]['actions'] = 33;
        $indicators[1]['quarter2'][3][3]['clients'] = 33;
        $indicators[2]['quarter2'][3][3]['actions'] = 0;
        $indicators[2]['quarter2'][3][3]['clients'] = 0;
        $indicators[3]['quarter2'][3][3]['actions'] = 62;
        $indicators[3]['quarter2'][3][3]['clients'] = 62;
        $indicators[4]['quarter2'][3][3]['actions'] = 8;
        $indicators[4]['quarter2'][3][3]['clients'] = 43;
        $indicators[5]['quarter2'][3][3]['actions'] = 27;
        $indicators[5]['quarter2'][3][3]['clients'] = 27;
        $indicators[6]['quarter2'][3][3]['actions'] = 12;
        $indicators[6]['quarter2'][3][3]['clients'] = 153;
        $indicators[7]['quarter2'][3][3]['actions'] = 32;
        $indicators[7]['quarter2'][3][3]['clients'] = 31;

        $indicators[0]['half1'][2][1]['actions'] = 7;
        $indicators[0]['half1'][2][1]['clients'] = 91;
        $indicators[1]['half1'][2][1]['actions'] = 25;
        $indicators[1]['half1'][2][1]['clients'] = 25;
        $indicators[2]['half1'][2][1]['actions'] = 0;
        $indicators[2]['half1'][2][1]['clients'] = 0;
        $indicators[3]['half1'][2][1]['actions'] = 33;
        $indicators[3]['half1'][2][1]['clients'] = 31;
        $indicators[4]['half1'][2][1]['actions'] = 7;
        $indicators[4]['half1'][2][1]['clients'] = 71;
        $indicators[5]['half1'][2][1]['actions'] = 4;
        $indicators[5]['half1'][2][1]['clients'] = 4;
        $indicators[6]['half1'][2][1]['actions'] = 4;
        $indicators[6]['half1'][2][1]['clients'] = 8;
        $indicators[7]['half1'][2][1]['actions'] = 57;
        $indicators[7]['half1'][2][1]['clients'] = 57;

        $indicators[0]['half1'][1][11]['actions'] = 6;
        $indicators[0]['half1'][1][11]['clients'] = 91;
        $indicators[1]['half1'][1][11]['actions'] = 10;
        $indicators[1]['half1'][1][11]['clients'] = 10;
        $indicators[2]['half1'][1][11]['actions'] = 2;
        $indicators[2]['half1'][1][11]['clients'] = 3;
        $indicators[3]['half1'][1][11]['actions'] = 9;
        $indicators[3]['half1'][1][11]['clients'] = 9;
        $indicators[4]['half1'][1][11]['actions'] = 14;
        $indicators[4]['half1'][1][11]['clients'] = 14;
        $indicators[5]['half1'][1][11]['actions'] = 20;
        $indicators[5]['half1'][1][11]['clients'] = 14;
        $indicators[6]['half1'][1][11]['actions'] = 12;
        $indicators[6]['half1'][1][11]['clients'] = 9;
        $indicators[7]['half1'][1][11]['actions'] = 11;
        $indicators[7]['half1'][1][11]['clients'] = 13;

        $indicators[0]['half1'][3][3]['actions'] = 16;
        $indicators[0]['half1'][3][3]['clients'] = 184;
        $indicators[1]['half1'][3][3]['actions'] = 41;
        $indicators[1]['half1'][3][3]['clients'] = 41;
        $indicators[2]['half1'][3][3]['actions'] = 0;
        $indicators[2]['half1'][3][3]['clients'] = 0;
        $indicators[3]['half1'][3][3]['actions'] = 89;
        $indicators[3]['half1'][3][3]['clients'] = 89;
        $indicators[4]['half1'][3][3]['actions'] = 10;
        $indicators[4]['half1'][3][3]['clients'] = 91;
        $indicators[5]['half1'][3][3]['actions'] = 40;
        $indicators[5]['half1'][3][3]['clients'] = 40;
        $indicators[6]['half1'][3][3]['actions'] = 21;
        $indicators[6]['half1'][3][3]['clients'] = 130;
        $indicators[7]['half1'][3][3]['actions'] = 41;
        $indicators[7]['half1'][3][3]['clients'] = 40;

        $indicators[0]['half2'][2][1]['actions'] = $indicators[0]['quarter3'][2][1]['actions'] + $indicators[0]['quarter4'][2][1]['actions'];
        $indicators[0]['half2'][2][1]['clients'] = $indicators[0]['quarter3'][2][1]['clients'] + $indicators[0]['quarter4'][2][1]['clients'] - 20;
        $indicators[1]['half2'][2][1]['actions'] = $indicators[1]['quarter3'][2][1]['actions'] + $indicators[1]['quarter4'][2][1]['actions'];
        $indicators[1]['half2'][2][1]['clients'] = $indicators[1]['quarter3'][2][1]['clients'] + $indicators[1]['quarter4'][2][1]['clients'] - 1;
        $indicators[2]['half2'][2][1]['actions'] = $indicators[2]['quarter3'][2][1]['actions'] + $indicators[2]['quarter4'][2][1]['actions'];
        $indicators[2]['half2'][2][1]['clients'] = $indicators[2]['quarter3'][2][1]['clients'] + $indicators[2]['quarter4'][2][1]['clients'];
        $indicators[3]['half2'][2][1]['actions'] = $indicators[3]['quarter3'][2][1]['actions'] + $indicators[3]['quarter4'][2][1]['actions'];
        $indicators[3]['half2'][2][1]['clients'] = $indicators[3]['quarter3'][2][1]['clients'] + $indicators[3]['quarter4'][2][1]['clients'];
        $indicators[4]['half2'][2][1]['actions'] = $indicators[4]['quarter3'][2][1]['actions'] + $indicators[4]['quarter4'][2][1]['actions'];
        $indicators[4]['half2'][2][1]['clients'] = $indicators[4]['quarter3'][2][1]['clients'] + $indicators[4]['quarter4'][2][1]['clients'] - 51;
        $indicators[5]['half2'][2][1]['actions'] = $indicators[5]['quarter3'][2][1]['actions'] + $indicators[5]['quarter4'][2][1]['actions'];
        $indicators[5]['half2'][2][1]['clients'] = $indicators[5]['quarter3'][2][1]['clients'] + $indicators[5]['quarter4'][2][1]['clients'];
        $indicators[6]['half2'][2][1]['actions'] = $indicators[6]['quarter3'][2][1]['actions'] + $indicators[6]['quarter4'][2][1]['actions'];
        $indicators[6]['half2'][2][1]['clients'] = $indicators[6]['quarter3'][2][1]['clients'] + $indicators[6]['quarter4'][2][1]['clients'];
        $indicators[7]['half2'][2][1]['actions'] = $indicators[7]['quarter3'][2][1]['actions'] + $indicators[7]['quarter4'][2][1]['actions'];
        $indicators[7]['half2'][2][1]['clients'] = $indicators[7]['quarter3'][2][1]['clients'] + $indicators[7]['quarter4'][2][1]['clients'] - 4;

        $indicators[0]['half2'][1][11]['actions'] = $indicators[0]['quarter3'][1][11]['actions'] + $indicators[0]['quarter4'][1][11]['actions'];
        $indicators[0]['half2'][1][11]['clients'] = $indicators[0]['quarter3'][1][11]['clients'] + $indicators[0]['quarter4'][1][11]['clients'] - 23;
        $indicators[1]['half2'][1][11]['actions'] = $indicators[1]['quarter3'][1][11]['actions'] + $indicators[1]['quarter4'][1][11]['actions'];
        $indicators[1]['half2'][1][11]['clients'] = $indicators[1]['quarter3'][1][11]['clients'] + $indicators[1]['quarter4'][1][11]['clients'];
        $indicators[2]['half2'][1][11]['actions'] = $indicators[2]['quarter3'][1][11]['actions'] + $indicators[2]['quarter4'][1][11]['actions'];
        $indicators[2]['half2'][1][11]['clients'] = $indicators[2]['quarter3'][1][11]['clients'] + $indicators[2]['quarter4'][1][11]['clients'];
        $indicators[3]['half2'][1][11]['actions'] = $indicators[3]['quarter3'][1][11]['actions'] + $indicators[3]['quarter4'][1][11]['actions'];
        $indicators[3]['half2'][1][11]['clients'] = $indicators[3]['quarter3'][1][11]['clients'] + $indicators[3]['quarter4'][1][11]['clients'] - 5;
        $indicators[4]['half2'][1][11]['actions'] = $indicators[4]['quarter3'][1][11]['actions'] + $indicators[4]['quarter4'][1][11]['actions'];
        $indicators[4]['half2'][1][11]['clients'] = $indicators[4]['quarter3'][1][11]['clients'] + $indicators[4]['quarter4'][1][11]['clients'] - 5;
        $indicators[5]['half2'][1][11]['actions'] = $indicators[5]['quarter3'][1][11]['actions'] + $indicators[5]['quarter4'][1][11]['actions'];
        $indicators[5]['half2'][1][11]['clients'] = $indicators[5]['quarter3'][1][11]['clients'] + $indicators[5]['quarter4'][1][11]['clients'];
        $indicators[6]['half2'][1][11]['actions'] = $indicators[6]['quarter3'][1][11]['actions'] + $indicators[6]['quarter4'][1][11]['actions'];
        $indicators[6]['half2'][1][11]['clients'] = $indicators[6]['quarter3'][1][11]['clients'] + $indicators[6]['quarter4'][1][11]['clients'] - 5;
        $indicators[7]['half2'][1][11]['actions'] = $indicators[7]['quarter3'][1][11]['actions'] + $indicators[7]['quarter4'][1][11]['actions'];
        $indicators[7]['half2'][1][11]['clients'] = $indicators[7]['quarter3'][1][11]['clients'] + $indicators[7]['quarter4'][1][11]['clients'];

        $indicators[0]['half2'][3][3]['actions'] = $indicators[0]['quarter3'][3][3]['actions'] + $indicators[0]['quarter4'][3][3]['actions'];
        $indicators[0]['half2'][3][3]['clients'] = $indicators[0]['quarter3'][3][3]['clients'] + $indicators[0]['quarter4'][3][3]['clients'] - 59;
        $indicators[1]['half2'][3][3]['actions'] = $indicators[1]['quarter3'][3][3]['actions'] + $indicators[1]['quarter4'][3][3]['actions'];
        $indicators[1]['half2'][3][3]['clients'] = $indicators[1]['quarter3'][3][3]['clients'] + $indicators[1]['quarter4'][3][3]['clients'] - 23;
        $indicators[2]['half2'][3][3]['actions'] = $indicators[2]['quarter3'][3][3]['actions'] + $indicators[2]['quarter4'][3][3]['actions'];
        $indicators[2]['half2'][3][3]['clients'] = $indicators[2]['quarter3'][3][3]['clients'] + $indicators[2]['quarter4'][3][3]['clients'];
        $indicators[3]['half2'][3][3]['actions'] = $indicators[3]['quarter3'][3][3]['actions'] + $indicators[3]['quarter4'][3][3]['actions'];
        $indicators[3]['half2'][3][3]['clients'] = $indicators[3]['quarter3'][3][3]['clients'] + $indicators[3]['quarter4'][3][3]['clients'] - 2;
        $indicators[4]['half2'][3][3]['actions'] = $indicators[4]['quarter3'][3][3]['actions'] + $indicators[4]['quarter4'][3][3]['actions'];
        $indicators[4]['half2'][3][3]['clients'] = $indicators[4]['quarter3'][3][3]['clients'] + $indicators[4]['quarter4'][3][3]['clients'] - 56;
        $indicators[5]['half2'][3][3]['actions'] = $indicators[5]['quarter3'][3][3]['actions'] + $indicators[5]['quarter4'][3][3]['actions'];
        $indicators[5]['half2'][3][3]['clients'] = $indicators[5]['quarter3'][3][3]['clients'] + $indicators[5]['quarter4'][3][3]['clients'];
        $indicators[6]['half2'][3][3]['actions'] = $indicators[6]['quarter3'][3][3]['actions'] + $indicators[6]['quarter4'][3][3]['actions'];
        $indicators[6]['half2'][3][3]['clients'] = $indicators[6]['quarter3'][3][3]['clients'] + $indicators[6]['quarter4'][3][3]['clients'] - 24;
        $indicators[7]['half2'][3][3]['actions'] = $indicators[7]['quarter3'][3][3]['actions'] + $indicators[7]['quarter4'][3][3]['actions'];
        $indicators[7]['half2'][3][3]['clients'] = $indicators[7]['quarter3'][3][3]['clients'] + $indicators[7]['quarter4'][3][3]['clients'] - 4;

        $indicators[0]['year'][2][1]['actions'] = $indicators[0]['half1'][2][1]['actions'] + $indicators[0]['half2'][2][1]['actions'];
        $indicators[0]['year'][2][1]['clients'] = $indicators[0]['half1'][2][1]['clients'] + $indicators[0]['half2'][2][1]['clients'] - 23;
        $indicators[1]['year'][2][1]['actions'] = $indicators[1]['half1'][2][1]['actions'] + $indicators[1]['half2'][2][1]['actions'];
        $indicators[1]['year'][2][1]['clients'] = $indicators[1]['half1'][2][1]['clients'] + $indicators[1]['half2'][2][1]['clients'] - 5;
        $indicators[2]['year'][2][1]['actions'] = $indicators[2]['half1'][2][1]['actions'] + $indicators[2]['half2'][2][1]['actions'];
        $indicators[2]['year'][2][1]['clients'] = $indicators[2]['half1'][2][1]['clients'] + $indicators[2]['half2'][2][1]['clients'];
        $indicators[3]['year'][2][1]['actions'] = $indicators[3]['half1'][2][1]['actions'] + $indicators[3]['half2'][2][1]['actions'];
        $indicators[3]['year'][2][1]['clients'] = $indicators[3]['half1'][2][1]['clients'] + $indicators[3]['half2'][2][1]['clients'];
        $indicators[4]['year'][2][1]['actions'] = $indicators[4]['half1'][2][1]['actions'] + $indicators[4]['half2'][2][1]['actions'];
        $indicators[4]['year'][2][1]['clients'] = $indicators[4]['half1'][2][1]['clients'] + $indicators[4]['half2'][2][1]['clients'] - 31;
        $indicators[5]['year'][2][1]['actions'] = $indicators[5]['half1'][2][1]['actions'] + $indicators[5]['half2'][2][1]['actions'];
        $indicators[5]['year'][2][1]['clients'] = $indicators[5]['half1'][2][1]['clients'] + $indicators[5]['half2'][2][1]['clients'];
        $indicators[6]['year'][2][1]['actions'] = $indicators[6]['half1'][2][1]['actions'] + $indicators[6]['half2'][2][1]['actions'];
        $indicators[6]['year'][2][1]['clients'] = $indicators[6]['half1'][2][1]['clients'] + $indicators[6]['half2'][2][1]['clients'];
        $indicators[7]['year'][2][1]['actions'] = $indicators[7]['half1'][2][1]['actions'] + $indicators[7]['half2'][2][1]['actions'];
        $indicators[7]['year'][2][1]['clients'] = $indicators[7]['half1'][2][1]['clients'] + $indicators[7]['half2'][2][1]['clients'] - 4;

        $indicators[0]['year'][1][11]['actions'] = $indicators[0]['half1'][1][11]['actions'] + $indicators[0]['half2'][1][11]['actions'];
        $indicators[0]['year'][1][11]['clients'] = $indicators[0]['half1'][1][11]['clients'] + $indicators[0]['half2'][1][11]['clients'] - 28;
        $indicators[1]['year'][1][11]['actions'] = $indicators[1]['half1'][1][11]['actions'] + $indicators[1]['half2'][1][11]['actions'];
        $indicators[1]['year'][1][11]['clients'] = $indicators[1]['half1'][1][11]['clients'] + $indicators[1]['half2'][1][11]['clients'];
        $indicators[2]['year'][1][11]['actions'] = $indicators[2]['half1'][1][11]['actions'] + $indicators[2]['half2'][1][11]['actions'];
        $indicators[2]['year'][1][11]['clients'] = $indicators[2]['half1'][1][11]['clients'] + $indicators[2]['half2'][1][11]['clients'] - 2;
        $indicators[3]['year'][1][11]['actions'] = $indicators[3]['half1'][1][11]['actions'] + $indicators[3]['half2'][1][11]['actions'];
        $indicators[3]['year'][1][11]['clients'] = $indicators[3]['half1'][1][11]['clients'] + $indicators[3]['half2'][1][11]['clients'];
        $indicators[4]['year'][1][11]['actions'] = $indicators[4]['half1'][1][11]['actions'] + $indicators[4]['half2'][1][11]['actions'];
        $indicators[4]['year'][1][11]['clients'] = $indicators[4]['half1'][1][11]['clients'] + $indicators[4]['half2'][1][11]['clients'] - 6;
        $indicators[5]['year'][1][11]['actions'] = $indicators[5]['half1'][1][11]['actions'] + $indicators[5]['half2'][1][11]['actions'];
        $indicators[5]['year'][1][11]['clients'] = $indicators[5]['half1'][1][11]['clients'] + $indicators[5]['half2'][1][11]['clients'] - 4;
        $indicators[6]['year'][1][11]['actions'] = $indicators[6]['half1'][1][11]['actions'] + $indicators[6]['half2'][1][11]['actions'];
        $indicators[6]['year'][1][11]['clients'] = $indicators[6]['half1'][1][11]['clients'] + $indicators[6]['half2'][1][11]['clients'] - 4;
        $indicators[7]['year'][1][11]['actions'] = $indicators[7]['half1'][1][11]['actions'] + $indicators[7]['half2'][1][11]['actions'];
        $indicators[7]['year'][1][11]['clients'] = $indicators[7]['half1'][1][11]['clients'] + $indicators[7]['half2'][1][11]['clients'] - 6;

        $indicators[0]['year'][3][3]['actions'] = $indicators[0]['half1'][3][3]['actions'] + $indicators[0]['half2'][3][3]['actions'];
        $indicators[0]['year'][3][3]['clients'] = $indicators[0]['half1'][3][3]['clients'] + $indicators[0]['half2'][3][3]['clients'] - 32;
        $indicators[1]['year'][3][3]['actions'] = $indicators[1]['half1'][3][3]['actions'] + $indicators[1]['half2'][3][3]['actions'];
        $indicators[1]['year'][3][3]['clients'] = $indicators[1]['half1'][3][3]['clients'] + $indicators[1]['half2'][3][3]['clients'] - 14;
        $indicators[2]['year'][3][3]['actions'] = $indicators[2]['half1'][3][3]['actions'] + $indicators[2]['half2'][3][3]['actions'];
        $indicators[2]['year'][3][3]['clients'] = $indicators[2]['half1'][3][3]['clients'] + $indicators[2]['half2'][3][3]['clients'];
        $indicators[3]['year'][3][3]['actions'] = $indicators[3]['half1'][3][3]['actions'] + $indicators[3]['half2'][3][3]['actions'];
        $indicators[3]['year'][3][3]['clients'] = $indicators[3]['half1'][3][3]['clients'] + $indicators[3]['half2'][3][3]['clients'] - 1;
        $indicators[4]['year'][3][3]['actions'] = $indicators[4]['half1'][3][3]['actions'] + $indicators[4]['half2'][3][3]['actions'];
        $indicators[4]['year'][3][3]['clients'] = $indicators[4]['half1'][3][3]['clients'] + $indicators[4]['half2'][3][3]['clients'] - 28;
        $indicators[5]['year'][3][3]['actions'] = $indicators[5]['half1'][3][3]['actions'] + $indicators[5]['half2'][3][3]['actions'];
        $indicators[5]['year'][3][3]['clients'] = $indicators[5]['half1'][3][3]['clients'] + $indicators[5]['half2'][3][3]['clients'] - 14;
        $indicators[6]['year'][3][3]['actions'] = $indicators[6]['half1'][3][3]['actions'] + $indicators[6]['half2'][3][3]['actions'];
        $indicators[6]['year'][3][3]['clients'] = $indicators[6]['half1'][3][3]['clients'] + $indicators[6]['half2'][3][3]['clients'] - 6;
        $indicators[7]['year'][3][3]['actions'] = $indicators[7]['half1'][3][3]['actions'] + $indicators[7]['half2'][3][3]['actions'];
        $indicators[7]['year'][3][3]['clients'] = $indicators[7]['half1'][3][3]['clients'] + $indicators[7]['half2'][3][3]['clients'] - 5;

        $indicators[0]['quarter1'][4][1]['actions'] = 6;
        $indicators[0]['quarter1'][4][1]['clients'] = 58;
        $indicators[1]['quarter1'][4][1]['actions'] = 0;
        $indicators[1]['quarter1'][4][1]['clients'] = 0;
        $indicators[2]['quarter1'][4][1]['actions'] = 0;
        $indicators[2]['quarter1'][4][1]['clients'] = 0;
        $indicators[3]['quarter1'][4][1]['actions'] = 4;
        $indicators[3]['quarter1'][4][1]['clients'] = 4;
        $indicators[4]['quarter1'][4][1]['actions'] = 0;
        $indicators[4]['quarter1'][4][1]['clients'] = 0;
        $indicators[5]['quarter1'][4][1]['actions'] = 0;
        $indicators[5]['quarter1'][4][1]['clients'] = 0;
        $indicators[6]['quarter1'][4][1]['actions'] = 0;
        $indicators[6]['quarter1'][4][1]['clients'] = 0;
        $indicators[7]['quarter1'][4][1]['actions'] = 23;
        $indicators[7]['quarter1'][4][1]['clients'] = 23;

        $indicators[0]['quarter1'][4][11]['actions'] = 0;
        $indicators[0]['quarter1'][4][11]['clients'] = 0;
        $indicators[1]['quarter1'][4][11]['actions'] = 0;
        $indicators[1]['quarter1'][4][11]['clients'] = 0;
        $indicators[2]['quarter1'][4][11]['actions'] = 0;
        $indicators[2]['quarter1'][4][11]['clients'] = 0;
        $indicators[3]['quarter1'][4][11]['actions'] = 2;
        $indicators[3]['quarter1'][4][11]['clients'] = 2;
        $indicators[4]['quarter1'][4][11]['actions'] = 0;
        $indicators[4]['quarter1'][4][11]['clients'] = 0;
        $indicators[5]['quarter1'][4][11]['actions'] = 0;
        $indicators[5]['quarter1'][4][11]['clients'] = 0;
        $indicators[6]['quarter1'][4][11]['actions'] = 0;
        $indicators[6]['quarter1'][4][11]['clients'] = 0;
        $indicators[7]['quarter1'][4][11]['actions'] = 12;
        $indicators[7]['quarter1'][4][11]['clients'] = 12;

        $indicators[0]['quarter1'][5][3]['actions'] = 0;
        $indicators[0]['quarter1'][5][3]['clients'] = 0;
        $indicators[1]['quarter1'][5][3]['actions'] = 0;
        $indicators[1]['quarter1'][5][3]['clients'] = 0;
        $indicators[2]['quarter1'][5][3]['actions'] = 0;
        $indicators[2]['quarter1'][5][3]['clients'] = 0;
        $indicators[3]['quarter1'][5][3]['actions'] = 3;
        $indicators[3]['quarter1'][5][3]['clients'] = 3;
        $indicators[4]['quarter1'][5][3]['actions'] = 0;
        $indicators[4]['quarter1'][5][3]['clients'] = 0;
        $indicators[5]['quarter1'][5][3]['actions'] = 0;
        $indicators[5]['quarter1'][5][3]['clients'] = 0;
        $indicators[6]['quarter1'][5][3]['actions'] = 0;
        $indicators[6]['quarter1'][5][3]['clients'] = 0;
        $indicators[7]['quarter1'][5][3]['actions'] = 0;
        $indicators[7]['quarter1'][5][3]['clients'] = 0;

        $indicators[0]['quarter2'][4][1]['actions'] = 4;
        $indicators[0]['quarter2'][4][1]['clients'] = 8;
        $indicators[1]['quarter2'][4][1]['actions'] = 0;
        $indicators[1]['quarter2'][4][1]['clients'] = 0;
        $indicators[2]['quarter2'][4][1]['actions'] = 0;
        $indicators[2]['quarter2'][4][1]['clients'] = 0;
        $indicators[3]['quarter2'][4][1]['actions'] = 7;
        $indicators[3]['quarter2'][4][1]['clients'] = 11;
        $indicators[4]['quarter2'][4][1]['actions'] = 0;
        $indicators[4]['quarter2'][4][1]['clients'] = 0;
        $indicators[5]['quarter2'][4][1]['actions'] = 0;
        $indicators[5]['quarter2'][4][1]['clients'] = 0;
        $indicators[6]['quarter2'][4][1]['actions'] = 0;
        $indicators[6]['quarter2'][4][1]['clients'] = 0;
        $indicators[7]['quarter2'][4][1]['actions'] = 2;
        $indicators[7]['quarter2'][4][1]['clients'] = 2;

        $indicators[0]['quarter2'][4][11]['actions'] = 5;
        $indicators[0]['quarter2'][4][11]['clients'] = 2;
        $indicators[1]['quarter2'][4][11]['actions'] = 0;
        $indicators[1]['quarter2'][4][11]['clients'] = 0;
        $indicators[2]['quarter2'][4][11]['actions'] = 0;
        $indicators[2]['quarter2'][4][11]['clients'] = 0;
        $indicators[3]['quarter2'][4][11]['actions'] = 9;
        $indicators[3]['quarter2'][4][11]['clients'] = 2;
        $indicators[4]['quarter2'][4][11]['actions'] = 0;
        $indicators[4]['quarter2'][4][11]['clients'] = 0;
        $indicators[5]['quarter2'][4][11]['actions'] = 1;
        $indicators[5]['quarter2'][4][11]['clients'] = 1;
        $indicators[6]['quarter2'][4][11]['actions'] = 0;
        $indicators[6]['quarter2'][4][11]['clients'] = 0;
        $indicators[7]['quarter2'][4][11]['actions'] = 0;
        $indicators[7]['quarter2'][4][11]['clients'] = 0;

        $indicators[0]['quarter2'][5][3]['actions'] = 0;
        $indicators[0]['quarter2'][5][3]['clients'] = 0;
        $indicators[1]['quarter2'][5][3]['actions'] = 0;
        $indicators[1]['quarter2'][5][3]['clients'] = 0;
        $indicators[2]['quarter2'][5][3]['actions'] = 0;
        $indicators[2]['quarter2'][5][3]['clients'] = 0;
        $indicators[3]['quarter2'][5][3]['actions'] = 10;
        $indicators[3]['quarter2'][5][3]['clients'] = 10;
        $indicators[4]['quarter2'][5][3]['actions'] = 0;
        $indicators[4]['quarter2'][5][3]['clients'] = 0;
        $indicators[5]['quarter2'][5][3]['actions'] = 0;
        $indicators[5]['quarter2'][5][3]['clients'] = 0;
        $indicators[6]['quarter2'][5][3]['actions'] = 0;
        $indicators[6]['quarter2'][5][3]['clients'] = 0;
        $indicators[7]['quarter2'][5][3]['actions'] = 0;
        $indicators[7]['quarter2'][5][3]['clients'] = 0;


        $indicators[0]['quarter3'][4][1]['actions'] = 0;
        $indicators[0]['quarter3'][4][1]['clients'] = 0;
        $indicators[1]['quarter3'][4][1]['actions'] = 0;
        $indicators[1]['quarter3'][4][1]['clients'] = 0;
        $indicators[2]['quarter3'][4][1]['actions'] = 0;
        $indicators[2]['quarter3'][4][1]['clients'] = 0;
        $indicators[3]['quarter3'][4][1]['actions'] = 0;
        $indicators[3]['quarter3'][4][1]['clients'] = 0;
        $indicators[4]['quarter3'][4][1]['actions'] = 0;
        $indicators[4]['quarter3'][4][1]['clients'] = 0;
        $indicators[5]['quarter3'][4][1]['actions'] = 0;
        $indicators[5]['quarter3'][4][1]['clients'] = 0;
        $indicators[6]['quarter3'][4][1]['actions'] = 0;
        $indicators[6]['quarter3'][4][1]['clients'] = 0;
        $indicators[7]['quarter3'][4][1]['actions'] = 0;
        $indicators[7]['quarter3'][4][1]['clients'] = 0;

        $indicators[0]['quarter3'][4][11]['actions'] = 0;
        $indicators[0]['quarter3'][4][11]['clients'] = 0;
        $indicators[1]['quarter3'][4][11]['actions'] = 0;
        $indicators[1]['quarter3'][4][11]['clients'] = 0;
        $indicators[2]['quarter3'][4][11]['actions'] = 0;
        $indicators[2]['quarter3'][4][11]['clients'] = 0;
        $indicators[3]['quarter3'][4][11]['actions'] = 1;
        $indicators[3]['quarter3'][4][11]['clients'] = 1;
        $indicators[4]['quarter3'][4][11]['actions'] = 0;
        $indicators[4]['quarter3'][4][11]['clients'] = 0;
        $indicators[5]['quarter3'][4][11]['actions'] = 0;
        $indicators[5]['quarter3'][4][11]['clients'] = 0;
        $indicators[6]['quarter3'][4][11]['actions'] = 0;
        $indicators[6]['quarter3'][4][11]['clients'] = 0;
        $indicators[7]['quarter3'][4][11]['actions'] = 0;
        $indicators[7]['quarter3'][4][11]['clients'] = 0;

        $indicators[0]['quarter3'][5][3]['actions'] = 0;
        $indicators[0]['quarter3'][5][3]['clients'] = 0;
        $indicators[1]['quarter3'][5][3]['actions'] = 0;
        $indicators[1]['quarter3'][5][3]['clients'] = 0;
        $indicators[2]['quarter3'][5][3]['actions'] = 0;
        $indicators[2]['quarter3'][5][3]['clients'] = 0;
        $indicators[3]['quarter3'][5][3]['actions'] = 18;
        $indicators[3]['quarter3'][5][3]['clients'] = 18;
        $indicators[4]['quarter3'][5][3]['actions'] = 1;
        $indicators[4]['quarter3'][5][3]['clients'] = 9;
        $indicators[5]['quarter3'][5][3]['actions'] = 15;
        $indicators[5]['quarter3'][5][3]['clients'] = 15;
        $indicators[6]['quarter3'][5][3]['actions'] = 0;
        $indicators[6]['quarter3'][5][3]['clients'] = 0;
        $indicators[7]['quarter3'][5][3]['actions'] = 10;
        $indicators[7]['quarter3'][5][3]['clients'] = 10;

        $indicators[0]['quarter4'][4][1]['actions'] = 0;
        $indicators[0]['quarter4'][4][1]['clients'] = 0;
        $indicators[1]['quarter4'][4][1]['actions'] = 2;
        $indicators[1]['quarter4'][4][1]['clients'] = 2;
        $indicators[2]['quarter4'][4][1]['actions'] = 0;
        $indicators[2]['quarter4'][4][1]['clients'] = 0;
        $indicators[3]['quarter4'][4][1]['actions'] = 2;
        $indicators[3]['quarter4'][4][1]['clients'] = 2;
        $indicators[4]['quarter4'][4][1]['actions'] = 0;
        $indicators[4]['quarter4'][4][1]['clients'] = 0;
        $indicators[5]['quarter4'][4][1]['actions'] = 1;
        $indicators[5]['quarter4'][4][1]['clients'] = 1;
        $indicators[6]['quarter4'][4][1]['actions'] = 0;
        $indicators[6]['quarter4'][4][1]['clients'] = 0;
        $indicators[7]['quarter4'][4][1]['actions'] = 4;
        $indicators[7]['quarter4'][4][1]['clients'] = 3;

        $indicators[0]['quarter4'][4][11]['actions'] = 0;
        $indicators[0]['quarter4'][4][11]['clients'] = 0;
        $indicators[1]['quarter4'][4][11]['actions'] = 0;
        $indicators[1]['quarter4'][4][11]['clients'] = 0;
        $indicators[2]['quarter4'][4][11]['actions'] = 0;
        $indicators[2]['quarter4'][4][11]['clients'] = 0;
        $indicators[3]['quarter4'][4][11]['actions'] = 0;
        $indicators[3]['quarter4'][4][11]['clients'] = 0;
        $indicators[4]['quarter4'][4][11]['actions'] = 0;
        $indicators[4]['quarter4'][4][11]['clients'] = 0;
        $indicators[5]['quarter4'][4][11]['actions'] = 0;
        $indicators[5]['quarter4'][4][11]['clients'] = 0;
        $indicators[6]['quarter4'][4][11]['actions'] = 0;
        $indicators[6]['quarter4'][4][11]['clients'] = 0;
        $indicators[7]['quarter4'][4][11]['actions'] = 0;
        $indicators[7]['quarter4'][4][11]['clients'] = 0;

        $indicators[0]['quarter4'][5][3]['actions'] = 0;
        $indicators[0]['quarter4'][5][3]['clients'] = 0;
        $indicators[1]['quarter4'][5][3]['actions'] = 0;
        $indicators[1]['quarter4'][5][3]['clients'] = 0;
        $indicators[2]['quarter4'][5][3]['actions'] = 0;
        $indicators[2]['quarter4'][5][3]['clients'] = 0;
        $indicators[3]['quarter4'][5][3]['actions'] = 7;
        $indicators[3]['quarter4'][5][3]['clients'] = 7;
        $indicators[4]['quarter4'][5][3]['actions'] = 0;
        $indicators[4]['quarter4'][5][3]['clients'] = 0;
        $indicators[5]['quarter4'][5][3]['actions'] = 3;
        $indicators[5]['quarter4'][5][3]['clients'] = 3;
        $indicators[6]['quarter4'][5][3]['actions'] = 0;
        $indicators[6]['quarter4'][5][3]['clients'] = 0;
        $indicators[7]['quarter4'][5][3]['actions'] = 2;
        $indicators[7]['quarter4'][5][3]['clients'] = 2;

        $indicators[0]['half1'][4][1]['actions'] = 10;
        $indicators[0]['half1'][4][1]['clients'] = 66;
        $indicators[1]['half1'][4][1]['actions'] = 0;
        $indicators[1]['half1'][4][1]['clients'] = 0;
        $indicators[2]['half1'][4][1]['actions'] = 0;
        $indicators[2]['half1'][4][1]['clients'] = 0;
        $indicators[3]['half1'][4][1]['actions'] = 11;
        $indicators[3]['half1'][4][1]['clients'] = 15;
        $indicators[4]['half1'][4][1]['actions'] = 0;
        $indicators[4]['half1'][4][1]['clients'] = 0;
        $indicators[5]['half1'][4][1]['actions'] = 0;
        $indicators[5]['half1'][4][1]['clients'] = 0;
        $indicators[6]['half1'][4][1]['actions'] = 0;
        $indicators[6]['half1'][4][1]['clients'] = 0;
        $indicators[7]['half1'][4][1]['actions'] = 25;
        $indicators[7]['half1'][4][1]['clients'] = 25;

        $indicators[0]['half1'][4][11]['actions'] = 5;
        $indicators[0]['half1'][4][11]['clients'] = 2;
        $indicators[1]['half1'][4][11]['actions'] = 0;
        $indicators[1]['half1'][4][11]['clients'] = 0;
        $indicators[2]['half1'][4][11]['actions'] = 0;
        $indicators[2]['half1'][4][11]['clients'] = 0;
        $indicators[3]['half1'][4][11]['actions'] = 11;
        $indicators[3]['half1'][4][11]['clients'] = 4;
        $indicators[4]['half1'][4][11]['actions'] = 0;
        $indicators[4]['half1'][4][11]['clients'] = 0;
        $indicators[5]['half1'][4][11]['actions'] = 1;
        $indicators[5]['half1'][4][11]['clients'] = 1;
        $indicators[6]['half1'][4][11]['actions'] = 0;
        $indicators[6]['half1'][4][11]['clients'] = 0;
        $indicators[7]['half1'][4][11]['actions'] = 12;
        $indicators[7]['half1'][4][11]['clients'] = 12;

        $indicators[0]['half1'][5][3]['actions'] = 0;
        $indicators[0]['half1'][5][3]['clients'] = 0;
        $indicators[1]['half1'][5][3]['actions'] = 0;
        $indicators[1]['half1'][5][3]['clients'] = 0;
        $indicators[2]['half1'][5][3]['actions'] = 0;
        $indicators[2]['half1'][5][3]['clients'] = 0;
        $indicators[3]['half1'][5][3]['actions'] = 13;
        $indicators[3]['half1'][5][3]['clients'] = 13;
        $indicators[4]['half1'][5][3]['actions'] = 0;
        $indicators[4]['half1'][5][3]['clients'] = 0;
        $indicators[5]['half1'][5][3]['actions'] = 0;
        $indicators[5]['half1'][5][3]['clients'] = 0;
        $indicators[6]['half1'][5][3]['actions'] = 0;
        $indicators[6]['half1'][5][3]['clients'] = 0;
        $indicators[7]['half1'][5][3]['actions'] = 0;
        $indicators[7]['half1'][5][3]['clients'] = 0;

        $indicators[0]['half2'][4][1]['actions'] = $indicators[0]['quarter3'][4][1]['actions'] + $indicators[0]['quarter4'][4][1]['actions'];
        $indicators[0]['half2'][4][1]['clients'] = $indicators[0]['quarter3'][4][1]['clients'] + $indicators[0]['quarter4'][4][1]['clients'];
        $indicators[1]['half2'][4][1]['actions'] = $indicators[1]['quarter3'][4][1]['actions'] + $indicators[1]['quarter4'][4][1]['actions'];
        $indicators[1]['half2'][4][1]['clients'] = $indicators[1]['quarter3'][4][1]['clients'] + $indicators[1]['quarter4'][4][1]['clients'];
        $indicators[2]['half2'][4][1]['actions'] = $indicators[2]['quarter3'][4][1]['actions'] + $indicators[2]['quarter4'][4][1]['actions'];
        $indicators[2]['half2'][4][1]['clients'] = $indicators[2]['quarter3'][4][1]['clients'] + $indicators[2]['quarter4'][4][1]['clients'];
        $indicators[3]['half2'][4][1]['actions'] = $indicators[3]['quarter3'][4][1]['actions'] + $indicators[3]['quarter4'][4][1]['actions'];
        $indicators[3]['half2'][4][1]['clients'] = $indicators[3]['quarter3'][4][1]['clients'] + $indicators[3]['quarter4'][4][1]['clients'];
        $indicators[4]['half2'][4][1]['actions'] = $indicators[4]['quarter3'][4][1]['actions'] + $indicators[4]['quarter4'][4][1]['actions'];
        $indicators[4]['half2'][4][1]['clients'] = $indicators[4]['quarter3'][4][1]['clients'] + $indicators[4]['quarter4'][4][1]['clients'];
        $indicators[5]['half2'][4][1]['actions'] = $indicators[5]['quarter3'][4][1]['actions'] + $indicators[5]['quarter4'][4][1]['actions'];
        $indicators[5]['half2'][4][1]['clients'] = $indicators[5]['quarter3'][4][1]['clients'] + $indicators[5]['quarter4'][4][1]['clients'];
        $indicators[6]['half2'][4][1]['actions'] = $indicators[6]['quarter3'][4][1]['actions'] + $indicators[6]['quarter4'][4][1]['actions'];
        $indicators[6]['half2'][4][1]['clients'] = $indicators[6]['quarter3'][4][1]['clients'] + $indicators[6]['quarter4'][4][1]['clients'];
        $indicators[7]['half2'][4][1]['actions'] = $indicators[7]['quarter3'][4][1]['actions'] + $indicators[7]['quarter4'][4][1]['actions'];
        $indicators[7]['half2'][4][1]['clients'] = $indicators[7]['quarter3'][4][1]['clients'] + $indicators[7]['quarter4'][4][1]['clients'];

        $indicators[0]['half2'][4][11]['actions'] = $indicators[0]['quarter3'][4][11]['actions'] + $indicators[0]['quarter4'][4][11]['actions'];
        $indicators[0]['half2'][4][11]['clients'] = $indicators[0]['quarter3'][4][11]['clients'] + $indicators[0]['quarter4'][4][11]['clients'];
        $indicators[1]['half2'][4][11]['actions'] = $indicators[1]['quarter3'][4][11]['actions'] + $indicators[1]['quarter4'][4][11]['actions'];
        $indicators[1]['half2'][4][11]['clients'] = $indicators[1]['quarter3'][4][11]['clients'] + $indicators[1]['quarter4'][4][11]['clients'];
        $indicators[2]['half2'][4][11]['actions'] = $indicators[2]['quarter3'][4][11]['actions'] + $indicators[2]['quarter4'][4][11]['actions'];
        $indicators[2]['half2'][4][11]['clients'] = $indicators[2]['quarter3'][4][11]['clients'] + $indicators[2]['quarter4'][4][11]['clients'];
        $indicators[3]['half2'][4][11]['actions'] = $indicators[3]['quarter3'][4][11]['actions'] + $indicators[3]['quarter4'][4][11]['actions'];
        $indicators[3]['half2'][4][11]['clients'] = $indicators[3]['quarter3'][4][11]['clients'] + $indicators[3]['quarter4'][4][11]['clients'];
        $indicators[4]['half2'][4][11]['actions'] = $indicators[4]['quarter3'][4][11]['actions'] + $indicators[4]['quarter4'][4][11]['actions'];
        $indicators[4]['half2'][4][11]['clients'] = $indicators[4]['quarter3'][4][11]['clients'] + $indicators[4]['quarter4'][4][11]['clients'];
        $indicators[5]['half2'][4][11]['actions'] = $indicators[5]['quarter3'][4][11]['actions'] + $indicators[5]['quarter4'][4][11]['actions'];
        $indicators[5]['half2'][4][11]['clients'] = $indicators[5]['quarter3'][4][11]['clients'] + $indicators[5]['quarter4'][4][11]['clients'];
        $indicators[6]['half2'][4][11]['actions'] = $indicators[6]['quarter3'][4][11]['actions'] + $indicators[6]['quarter4'][4][11]['actions'];
        $indicators[6]['half2'][4][11]['clients'] = $indicators[6]['quarter3'][4][11]['clients'] + $indicators[6]['quarter4'][4][11]['clients'];
        $indicators[7]['half2'][4][11]['actions'] = $indicators[7]['quarter3'][4][11]['actions'] + $indicators[7]['quarter4'][4][11]['actions'];
        $indicators[7]['half2'][4][11]['clients'] = $indicators[7]['quarter3'][4][11]['clients'] + $indicators[7]['quarter4'][4][11]['clients'];

        $indicators[0]['half2'][5][3]['actions'] = $indicators[0]['quarter3'][5][3]['actions'] + $indicators[0]['quarter4'][5][3]['actions'];
        $indicators[0]['half2'][5][3]['clients'] = $indicators[0]['quarter3'][5][3]['clients'] + $indicators[0]['quarter4'][5][3]['clients'];
        $indicators[1]['half2'][5][3]['actions'] = $indicators[1]['quarter3'][5][3]['actions'] + $indicators[1]['quarter4'][5][3]['actions'];
        $indicators[1]['half2'][5][3]['clients'] = $indicators[1]['quarter3'][5][3]['clients'] + $indicators[1]['quarter4'][5][3]['clients'];
        $indicators[2]['half2'][5][3]['actions'] = $indicators[2]['quarter3'][5][3]['actions'] + $indicators[2]['quarter4'][5][3]['actions'];
        $indicators[2]['half2'][5][3]['clients'] = $indicators[2]['quarter3'][5][3]['clients'] + $indicators[2]['quarter4'][5][3]['clients'];
        $indicators[3]['half2'][5][3]['actions'] = $indicators[3]['quarter3'][5][3]['actions'] + $indicators[3]['quarter4'][5][3]['actions'];
        $indicators[3]['half2'][5][3]['clients'] = $indicators[3]['quarter3'][5][3]['clients'] + $indicators[3]['quarter4'][5][3]['clients'];
        $indicators[4]['half2'][5][3]['actions'] = $indicators[4]['quarter3'][5][3]['actions'] + $indicators[4]['quarter4'][5][3]['actions'];
        $indicators[4]['half2'][5][3]['clients'] = $indicators[4]['quarter3'][5][3]['clients'] + $indicators[4]['quarter4'][5][3]['clients'];
        $indicators[5]['half2'][5][3]['actions'] = $indicators[5]['quarter3'][5][3]['actions'] + $indicators[5]['quarter4'][5][3]['actions'];
        $indicators[5]['half2'][5][3]['clients'] = $indicators[5]['quarter3'][5][3]['clients'] + $indicators[5]['quarter4'][5][3]['clients'];
        $indicators[6]['half2'][5][3]['actions'] = $indicators[6]['quarter3'][5][3]['actions'] + $indicators[6]['quarter4'][5][3]['actions'];
        $indicators[6]['half2'][5][3]['clients'] = $indicators[6]['quarter3'][5][3]['clients'] + $indicators[6]['quarter4'][5][3]['clients'];
        $indicators[7]['half2'][5][3]['actions'] = $indicators[7]['quarter3'][5][3]['actions'] + $indicators[7]['quarter4'][5][3]['actions'];
        $indicators[7]['half2'][5][3]['clients'] = $indicators[7]['quarter3'][5][3]['clients'] + $indicators[7]['quarter4'][5][3]['clients'];

        $indicators[0]['year'][4][1]['actions'] = $indicators[0]['half1'][4][1]['actions'] + $indicators[0]['half2'][4][1]['actions'];
        $indicators[0]['year'][4][1]['clients'] = $indicators[0]['half1'][4][1]['clients'] + $indicators[0]['half2'][4][1]['clients'];
        $indicators[1]['year'][4][1]['actions'] = $indicators[1]['half1'][4][1]['actions'] + $indicators[1]['half2'][4][1]['actions'];
        $indicators[1]['year'][4][1]['clients'] = $indicators[1]['half1'][4][1]['clients'] + $indicators[1]['half2'][4][1]['clients'];
        $indicators[2]['year'][4][1]['actions'] = $indicators[2]['half1'][4][1]['actions'] + $indicators[2]['half2'][4][1]['actions'];
        $indicators[2]['year'][4][1]['clients'] = $indicators[2]['half1'][4][1]['clients'] + $indicators[2]['half2'][4][1]['clients'];
        $indicators[3]['year'][4][1]['actions'] = $indicators[3]['half1'][4][1]['actions'] + $indicators[3]['half2'][4][1]['actions'];
        $indicators[3]['year'][4][1]['clients'] = $indicators[3]['half1'][4][1]['clients'] + $indicators[3]['half2'][4][1]['clients'];
        $indicators[4]['year'][4][1]['actions'] = $indicators[4]['half1'][4][1]['actions'] + $indicators[4]['half2'][4][1]['actions'];
        $indicators[4]['year'][4][1]['clients'] = $indicators[4]['half1'][4][1]['clients'] + $indicators[4]['half2'][4][1]['clients'];
        $indicators[5]['year'][4][1]['actions'] = $indicators[5]['half1'][4][1]['actions'] + $indicators[5]['half2'][4][1]['actions'];
        $indicators[5]['year'][4][1]['clients'] = $indicators[5]['half1'][4][1]['clients'] + $indicators[5]['half2'][4][1]['clients'];
        $indicators[6]['year'][4][1]['actions'] = $indicators[6]['half1'][4][1]['actions'] + $indicators[6]['half2'][4][1]['actions'];
        $indicators[6]['year'][4][1]['clients'] = $indicators[6]['half1'][4][1]['clients'] + $indicators[6]['half2'][4][1]['clients'];
        $indicators[7]['year'][4][1]['actions'] = $indicators[7]['half1'][4][1]['actions'] + $indicators[7]['half2'][4][1]['actions'];
        $indicators[7]['year'][4][1]['clients'] = $indicators[7]['half1'][4][1]['clients'] + $indicators[7]['half2'][4][1]['clients'];

        $indicators[0]['year'][4][11]['actions'] = $indicators[0]['half1'][4][11]['actions'] + $indicators[0]['half2'][4][11]['actions'];
        $indicators[0]['year'][4][11]['clients'] = $indicators[0]['half1'][4][11]['clients'] + $indicators[0]['half2'][4][11]['clients'];
        $indicators[1]['year'][4][11]['actions'] = $indicators[1]['half1'][4][11]['actions'] + $indicators[1]['half2'][4][11]['actions'];
        $indicators[1]['year'][4][11]['clients'] = $indicators[1]['half1'][4][11]['clients'] + $indicators[1]['half2'][4][11]['clients'];
        $indicators[2]['year'][4][11]['actions'] = $indicators[2]['half1'][4][11]['actions'] + $indicators[2]['half2'][4][11]['actions'];
        $indicators[2]['year'][4][11]['clients'] = $indicators[2]['half1'][4][11]['clients'] + $indicators[2]['half2'][4][11]['clients'];
        $indicators[3]['year'][4][11]['actions'] = $indicators[3]['half1'][4][11]['actions'] + $indicators[3]['half2'][4][11]['actions'];
        $indicators[3]['year'][4][11]['clients'] = $indicators[3]['half1'][4][11]['clients'] + $indicators[3]['half2'][4][11]['clients'];
        $indicators[4]['year'][4][11]['actions'] = $indicators[4]['half1'][4][11]['actions'] + $indicators[4]['half2'][4][11]['actions'];
        $indicators[4]['year'][4][11]['clients'] = $indicators[4]['half1'][4][11]['clients'] + $indicators[4]['half2'][4][11]['clients'];
        $indicators[5]['year'][4][11]['actions'] = $indicators[5]['half1'][4][11]['actions'] + $indicators[5]['half2'][4][11]['actions'];
        $indicators[5]['year'][4][11]['clients'] = $indicators[5]['half1'][4][11]['clients'] + $indicators[5]['half2'][4][11]['clients'];
        $indicators[6]['year'][4][11]['actions'] = $indicators[6]['half1'][4][11]['actions'] + $indicators[6]['half2'][4][11]['actions'];
        $indicators[6]['year'][4][11]['clients'] = $indicators[6]['half1'][4][11]['clients'] + $indicators[6]['half2'][4][11]['clients'];
        $indicators[7]['year'][4][11]['actions'] = $indicators[7]['half1'][4][11]['actions'] + $indicators[7]['half2'][4][11]['actions'];
        $indicators[7]['year'][4][11]['clients'] = $indicators[7]['half1'][4][11]['clients'] + $indicators[7]['half2'][4][11]['clients'];

        $indicators[0]['year'][5][3]['actions'] = $indicators[0]['half1'][5][3]['actions'] + $indicators[0]['half2'][5][3]['actions'];
        $indicators[0]['year'][5][3]['clients'] = $indicators[0]['half1'][5][3]['clients'] + $indicators[0]['half2'][5][3]['clients'];
        $indicators[1]['year'][5][3]['actions'] = $indicators[1]['half1'][5][3]['actions'] + $indicators[1]['half2'][5][3]['actions'];
        $indicators[1]['year'][5][3]['clients'] = $indicators[1]['half1'][5][3]['clients'] + $indicators[1]['half2'][5][3]['clients'];
        $indicators[2]['year'][5][3]['actions'] = $indicators[2]['half1'][5][3]['actions'] + $indicators[2]['half2'][5][3]['actions'];
        $indicators[2]['year'][5][3]['clients'] = $indicators[2]['half1'][5][3]['clients'] + $indicators[2]['half2'][5][3]['clients'];
        $indicators[3]['year'][5][3]['actions'] = $indicators[3]['half1'][5][3]['actions'] + $indicators[3]['half2'][5][3]['actions'];
        $indicators[3]['year'][5][3]['clients'] = $indicators[3]['half1'][5][3]['clients'] + $indicators[3]['half2'][5][3]['clients'];
        $indicators[4]['year'][5][3]['actions'] = $indicators[4]['half1'][5][3]['actions'] + $indicators[4]['half2'][5][3]['actions'];
        $indicators[4]['year'][5][3]['clients'] = $indicators[4]['half1'][5][3]['clients'] + $indicators[4]['half2'][5][3]['clients'];
        $indicators[5]['year'][5][3]['actions'] = $indicators[5]['half1'][5][3]['actions'] + $indicators[5]['half2'][5][3]['actions'];
        $indicators[5]['year'][5][3]['clients'] = $indicators[5]['half1'][5][3]['clients'] + $indicators[5]['half2'][5][3]['clients'];
        $indicators[6]['year'][5][3]['actions'] = $indicators[6]['half1'][5][3]['actions'] + $indicators[6]['half2'][5][3]['actions'];
        $indicators[6]['year'][5][3]['clients'] = $indicators[6]['half1'][5][3]['clients'] + $indicators[6]['half2'][5][3]['clients'];
        $indicators[7]['year'][5][3]['actions'] = $indicators[7]['half1'][5][3]['actions'] + $indicators[7]['half2'][5][3]['actions'];
        $indicators[7]['year'][5][3]['clients'] = $indicators[7]['half1'][5][3]['clients'] + $indicators[7]['half2'][5][3]['clients'];

        return view('pages.statistics.activityClients')->with(
            [
                'project' => $request->project,
                'indicators' => $indicators,
                'activitiesClients' => $activitiesClients,
            ]
        );
    }

    public function dump(Request $request)
    {

        function sendMailAttachment($mailTo, $From, $subject_text, $message, $file)
        {

            $to = $mailTo;

            $EOL = "\r\n";                      // ограничитель строк, некоторые почтовые сервера требуют \n - подобрать опытным путём
            $boundary = "--" . md5(uniqid(time()));  // любая строка, которой не будет ниже в потоке данных.

            $subject = '=?utf-8?B?' . base64_encode($subject_text) . '?=';

            $headers = "MIME-Version: 1.0;$EOL";
            $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"$EOL";
            $headers .= "From: $From\nReply-To: $From\n";

            $multipart = "--$boundary$EOL";
            $multipart .= "Content-Type: text/html; charset=utf-8$EOL";
            $multipart .= "Content-Transfer-Encoding: base64$EOL";
            $multipart .= $EOL; // раздел между заголовками и телом html-части
            $multipart .= chunk_split(base64_encode($message));

            #начало вставки файлов

            $filename = $file->getPathname();
            $file = fopen($filename, "rb");
            $data = fread($file, filesize($filename));
            fclose($file);
            $NameFile = 'data.xlsx'; // в этой переменной надо сформировать имя файла (без всякого пути);
            $File = $data;
            $multipart .= "$EOL--$boundary$EOL";
            $multipart .= "Content-Type: application/octet-stream; name=\"$NameFile\"$EOL";
            $multipart .= "Content-Transfer-Encoding: base64$EOL";
            $multipart .= "Content-Disposition: attachment; filename=\"$NameFile\"$EOL";
            $multipart .= $EOL; // раздел между заголовками и телом прикрепленного файла
            $multipart .= chunk_split(base64_encode($File));

            #>>конец вставки файлов

            $multipart .= "$EOL--$boundary--$EOL";

            mail($to, $subject, $multipart, $headers);
        }
        $userId = ExportTempExcel::where('author', auth()->user()->id)->get()->first()->user;
        $email = User::whereNameRu($userId)->get()->first()->email;
        $ids = ExportTempExcel::where('author', auth()->user()->id)->select('id')->get()->toArray();
        foreach ($ids as $key => $id) {
            $returnIds[] = $id['id'];
        }
//        Activity::whereIn('id', $returnIds)->update(['status' => 2]);

        $to = auth()->user()->email;
        $to .= ",".$email;

        $subject = "Архивация деятельности: ".$userId;
        $message = '<html><body><h2>Деятельность архивирована</h2></body></html>';
        sendMailAttachment($to, 'info@intilsih.uz', $subject, $message, Excel::download(new VerificationExport, 'data.xlsx')->getFile());

        return Excel::download(new VerificationExport, 'data.xlsx');
    }

    public function activity(Request $request)
    {
        $mainActivity = Activity::find($request->id);
        $assignment = Assignment::find($mainActivity->assignment);
        $allActivities = Activity::whereAssignment($assignment->id)->get();
        $user = User::find($mainActivity->user);

        foreach ($user->position as $item) {
            $responsibility = Responsibilities::where('position', 'LIKE', '%"' . $item . '"%')->get();
        }


//            $responsibility = Responsibilities::where('user', '=')

        $users = User::all();

        return view('pages.statistics.activityTemp')->with(
            [
                'activities' => $allActivities,
                'assignment' => $assignment,
                'mainActivity' => $mainActivity,
                'users' => $users
            ]
        );

    }

    public function verification(Request $request)
    {
        $data = Activity::leftJoin('assignments', 'assignments.id', '=', 'activities.assignment');
        if ($request->project !== 'all') $data = $data->where('assignments.project', $request->project);
        $data = $data->where('activities.user', $request->user)
            ->where('activities.status', '=', 1)
            ->whereBetween('activities.date', [$request->start, $request->end])
            ->select('activities.id')->get();


        foreach ($data as $datum) {
            $ids[] = $datum->id;
        }

        Activity::whereIn('id', $ids)->update(['status' => 2]);
        return redirect()->back();

    }

}
