<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Assignment;
use App\Client;
use App\Position;
use App\Project;
use App\Region;
use App\Responsibilities;
use App\User;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Author;

class ActivityController extends Controller
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

    public function index(Request $request)
    {
        if (auth()->user()->role === 1) $projects = Project::select('id', 'encoding', 'status')->get();
        else {
            $positions = Position::whereIn('id', auth()->user()->position)->get();
            foreach ($positions as $position) {
                $project_id[] = $position->project;
            }
            $projects = Project::whereIn('id', $project_id)->select('id', 'encoding', 'status')->get();
        }
        return view('pages.activity.projects')->with(['projects' => $projects]);
    }

    public function Users(Request $request)
    {

        $positions = Position::whereProject($request->project)->where('status', '>=', 1)->get();

        $regions = Region::all();

        foreach ($positions as $position) {
            $users = User::where('users.position', 'like', '%"' . $position->id . '"%')
                ->where(['users.status' => 1])
                ->select('users.id', 'users.name_' . app()->getLocale() . ' as name')
                ->get();
            foreach ($users as $user) {
                if ($user->id === auth()->user()->id) {
                    $userThis = $user;
                    $userThis->position = $position->id;
                }
                $users_result[auth()->user()->project->id][$regions->find($position->region)->encoding][$user->id]['name'] = $user->name;
                $users_result[auth()->user()->project->id][$regions->find($position->region)->encoding][$user->id]['position'] = $position->id;
            }
        }

        return view('pages.activity.activity')->with(['users' => $users_result, 'userThis' => $userThis]);
    }

    public function redirectAssignment(Request $request)
    {
        $assignment = Assignment::find($request->assignment);

        $positions = array_merge(array_keys((array)$assignment->administrants), array_keys((array)$assignment->helpers), array_keys((array)$assignment->supervisors));

        foreach ($positions as $key => $value) {
            $users = User::where('status', 1)
                ->where('position', 'LIKE', '%"' . $value . '"%')
                ->select('name_' . app()->getLocale() . ' as name', 'id')
                ->get();

            $packUsers[] = User::where('status', 1)
                ->where('position', 'LIKE', '%"' . $value . '"%')
                ->select('name_' . app()->getLocale() . ' as name', 'id')
                ->first();

            foreach ($users as $user){
                $user->id == $request->id ? $position = $value : '';
            }
        }



        $clients = Client::where('prison', $assignment->prison)->get();

        $user = User::where('id', $request->id)->select('name_' . app()->getLocale() . ' as name')->first();

        return view('pages.activity.activityAssignment')->with(['assignment' => $assignment, 'clients' => $clients, 'user_id' => $request->id, 'user_name' => $user->name, 'date' => $request->date, 'users' => $packUsers, 'position' => $position]);
    }

    public function redirectUser(Request $request)
    {
        auth()->user()->role > 2 ? auth()->user()->id != $request->id ? abort('403') : '' : '';
        $user = User::where('id', $request->id)->select('name_' . app()->getLocale() . ' as name')->first();

        $position = Position::find($request->position);

        define(position, $request->position);

        $assignments = Assignment::where('status', '>=', 1)
            ->where('start', '<=', $request->date . ' 09:00:00')
            ->where('end', '>=', $request->date . ' 17:00:00')
            ->where(
                function ($query) {
                    $query->orWhere('administrants', 'like', '%"' . position . '":%');
                    $query->orWhere('helpers', 'like', '%"' . position . '":%');
                    $query->orWhere('supervisors', 'like', '%"' . position . '":%');
                }
            )->select('id', 'mark', 'service')
            ->orderBy('mark');

        if(position == 76) $assignments = $assignments->where('author', '=', auth()->user()->id)->get();
        else $assignments = $assignments->get();

        $activities = Activity::leftJoin('users', 'users.id', '=', 'activities.user')
            ->leftJoin('assignments', 'assignments.id', '=', 'activities.assignment')
            ->where('activities.user', $request->id)
            ->where('activities.date', '=', $request->date)
            ->where('activities.status', '>=', 1)
            ->select('activities.id', 'users.name_' . app()->getLocale() . ' as user', 'assignments.mark as assignment', 'activities.date', 'activities.start', 'activities.end')
            ->orderBy('date', 'desc')
            ->get();

        return view('pages.activity.activityUser')->with(['user_id' => $request->id, 'user_name' => $user->name, 'date' => $request->date, 'assignments' => $assignments, 'activities' => $activities, 'position' => $position]);
    }

    public function tempRegistration(Request $request)
    {

        $users = User::where(['users.status' => 1])
            ->select('users.id', 'users.name_ru', 'users.position')
            ->get();

        foreach ($users as $user) {
            $positions = Position::leftJoin('regions', 'regions.id', '=', 'positions.region')
                ->whereProject(4)
                ->whereIn('positions.id', $user->position)
                ->orderBy('regions.id')
                ->first();

            if ($positions->project) {
                $users_result[$positions->project][$positions->encoding][$user->id] = $user->name_ru;
            }
        }
        return view('pages.activity.activityTempRegistration')->with(['users' => $users_result]);
    }

    public function tempRegistrationUser(Request $request)
    {

        $clients = Client::all();

        $activities = Activity::leftJoin('users', 'users.id', '=', 'activities.user')
            ->leftJoin('assignments', 'assignments.id', '=', 'activities.assignment')
            ->where('activities.user', $request->user)
            ->where('activities.status', '>', 0)
            ->select('activities.id', 'users.name_' . app()->getLocale() . ' as user', 'assignments.mark as assignment', 'activities.date', 'activities.start', 'activities.end', 'activities.status')
            ->orderBy('date', 'desc')
            ->get();

        $users = User::where(['users.status' => 1])
            ->select('users.id', 'users.name_ru', 'users.position')
            ->get();


        foreach ($users as $user) {
            $positions = Position::leftJoin('regions', 'regions.id', '=', 'positions.region')
                ->whereIn('positions.id', $user->position)
                ->select('positions.*', 'regions.encoding as encoding')
                ->first();

            if ($positions->project) {
                $users_result[$positions->project][$positions->encoding][$user->id] = $user->name_ru;
            }
        }

        $user = User::find($request->user);

        $responsibilities = Responsibilities::where('position', 'like', '%"' . $request->position . '"%')
            ->select('id', 'name_' . app()->getLocale() . ' as name')
            ->get();

        return view('pages.activity.activityTempRegistrationUser')->with(['clients' => $clients, 'responsibilities' => $responsibilities, 'user' => $user, 'users' => $users_result, 'activities' => $activities, 'position' => $request->position]);
    }

    public function add(Request $request)
    {
        foreach ($request->users as $item) {
            Activity::create(
                [
                    'author' => auth()->user()->id,
                    'user' => $item,
                    'assignment' => $request->assignment,
                    'date' => $request->date,
                    'start' => $request->start,
                    'end' => $request->end,
                    'comment' => $request->comment,
                    'clients' => $request->clients
                ]
            );
        };

        Activity::create(
            [
                'author' => auth()->user()->id,
                'user' => $request->user,
                'assignment' => $request->assignment,
                'date' => $request->date,
                'start' => $request->start,
                'end' => $request->end,
                'comment' => $request->comment,
                'clients' => $request->clients
            ]
        );

        return redirect()->route('activityRedirectUser', ['id' => $request->user, 'date' => $request->date, 'position' => $request->position]);
    }

    public function addTemp(Request $request)
    {
        foreach ($request->users as $item) {
            $positions[User::find($item)->position[0]] = $request->responsibility;
        }

        $positions[$request->position] = $request->responsibility;

        $project = Position::find($request->position)->project;

        $insert_id = Assignment::create(
            [
                'author' => auth()->user()->id,
                'project' => $project,
                'mark' => 'Временное поручение (на 4 квартал)',
                'administrants' => $positions,
                'start' => '2019-01-01 09:00:00',
                'end' => '2019-06-30 17:00:00',
                'text' => 'Временное поручение (на 4 квартал)'
            ]
        )->id;

        foreach ($request->users as $item) {
            Activity::create(
                [
                    'author' => auth()->user()->id,
                    'user' => $item,
                    'assignment' => $insert_id,
                    'date' => $request->date,
                    'start' => $request->start,
                    'end' => $request->end,
                    'comment' => $request->comment,
                    'clients' => $request->clients
                ]
            );
        }

        Activity::create(
            [
                'author' => auth()->user()->id,
                'user' => $request->user,
                'assignment' => $insert_id,
                'date' => $request->date,
                'start' => $request->start,
                'end' => $request->end,
                'comment' => $request->comment,
                'clients' => $request->clients
            ]
        );

        return redirect()->back();
    }

    public function tempRegistrationPosition(Request $request)
    {
        $user = User::find($request->user);
        $positions = Position::whereIn('id', $user->position)->get();

        if ($positions->count() == 1) {
            return redirect()->route('tempRegistrationUser', ['user' => $request->user, 'positions' => $positions[0]->id]);
        } else {
            return view('pages.activity.activityTempRegistrationPosition')->with(['positions' => $positions, 'user' => $request->user]);
        }


    }
}
