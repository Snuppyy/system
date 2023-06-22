<?php /** @noinspection PhpUndefinedClassInspection */

namespace App\Http\Controllers;

use App\Answer;
use App\Client;
use App\Drop_inCenter;
use App\NoQuestWebinar;
use App\Outreach;
use App\Position;
use App\Questionnaire;
use App\QuestionnaireOPU_001;
use App\Region;
use App\TypesDrug;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionnairesController extends Controller
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
        $data = DB::table('questionnaires')->where('questionnaires.encoding', $request->encoding)
            ->join('questions', 'questions.id_questionnaire', '=', 'questionnaires.id')
            ->join('variants_of_answers', 'variants_of_answers.id_question', '=', 'questions.id')
            ->select('questions.name_' . app()->getLocale() . ' as question', 'questions.id as id_question', 'questions.type as type', 'questionnaires.name_' . app()->getLocale() . ' as questionnaire', 'questionnaires.id as id_questionnaire', 'variants_of_answers.id as id_answer', 'variants_of_answers.name_' . app()->getLocale() . ' as answer')
            ->orderBy('variants_of_answers.id_question')
            ->get();

        foreach ($data as $array) {
            $questionnaire['name'] = $array->questionnaire;
            $questionnaire['id'] = $array->id_questionnaire;
            $questionnaire['encoding'] = $request->encoding;
            $questionnaire[$array->id_question]['question'] = $array->question;
            $questionnaire[$array->id_question]['type'] = $array->type;
            $questionnaire[$array->id_question]['answers'][$array->id_answer] = $array->answer;
        }


        $questionnaireId = Questionnaire::where('encoding', $request->encoding)->select('id', 'name_' . app()->getLocale() . ' as questionnaire')->first();

        $noQuest = NoQuestWebinar::where('questionnaire', $questionnaireId->id)->get();

        $questionnaire && $noQuest ?? abort(404);

        $questionnaire['id'] = $questionnaireId->id;
        $questionnaire['name'] = $questionnaireId->questionnaire;
        $questionnaire['encoding'] = $request->encoding;

        $outreaches = Outreach::where('region', \Auth::user()->region->id === 0 ? 1 : \Auth::user()->region->id)->where('status', 1)->select(\DB::raw('CONCAT(f_name, " ", s_name) as name'), 'encoding', 'id')->orderBy('f_name')->get();

        return view('pages.questionnaiares.questionnaires')->with(['questionnaire' => $questionnaire, 'outreaches' => $outreaches]);
    }

    public function indexTb(Request $request)
    {
        $data = DB::table('questionnaires')->where('questionnaires.encoding', $request->encoding)
            ->join('questions', 'questions.id_questionnaire', '=', 'questionnaires.id')
            ->join('variants_of_answers', 'variants_of_answers.id_question', '=', 'questions.id')
            ->select('questions.name_' . app()->getLocale() . ' as question', 'questions.id as id_question', 'questions.type as type', 'questionnaires.name_' . app()->getLocale() . ' as questionnaire', 'questionnaires.id as id_questionnaire', 'variants_of_answers.id as id_answer', 'variants_of_answers.name_' . app()->getLocale() . ' as answer')
            ->get();

        foreach ($data as $array) {
            $questionnaire['name'] = $array->questionnaire;
            $questionnaire['id'] = $array->id_questionnaire;
            $questionnaire['encoding'] = $request->encoding;
            $questionnaire[$array->id_question]['question'] = $array->question;
            $questionnaire[$array->id_question]['type'] = $array->type;
            $questionnaire[$array->id_question]['answers'][$array->id_answer] = $array->answer;
        }


        $questionnaireId = Questionnaire::where('encoding', $request->encoding)->select('id', 'name_' . app()->getLocale() . ' as questionnaire')->first();

        $noQuest = NoQuestWebinar::where('questionnaire', $questionnaireId->id)->get();

        $questionnaire && $noQuest ?? abort(404);

        $questionnaire['id'] = $questionnaireId->id;
        $questionnaire['name'] = $questionnaireId->questionnaire;
        $questionnaire['encoding'] = $request->encoding;

        $regions = Region::select('encoding', 'id')->whereIn('id', [1, 3, 11])->get();

        $clients = Client::where('region', \Auth::user()->region->id === 0 ? 1 : \Auth::user()->region->id)->where('status', 1)->select(\DB::raw('CONCAT(f_name, " ", s_name) as name'), 'id')->orderBy('f_name')->get();

        return view('pages.questionnaiares.questionnaires_tb')->with(['questionnaire' => $questionnaire, 'clients' => $clients, 'regions' => $regions]);
    }

    public function view(Request $request)
    {
        if (\Auth::user()->region->id === 0) {
            $data = DB::table('answers')->where('answers.status', '>=', 1)->where(['answers.project' => 2]);
            $outreach = DB::table('answers')->where('answers.status', '>=', 1)->where(['answers.project' => 2]);
            $volunteer = DB::table('answers')->where('answers.status', '>=', 1)->where(['answers.project' => 2]);
            $duplicates = DB::table('answers')->where('answers.status', '>=', 1)->where(['answers.project' => 2]);
            $noScan = DB::table('answers')->where('answers.status', '>=', 1)->where(['answers.project' => 2]);
            $webinars = DB::table('answers')->where('answers.status', '>=', 1)->where(['answers.project' => 2]);
            $seminars = DB::table('answers')->where('answers.status', '>=', 1)->where(['answers.project' => 2]);
            $webinarsAns = DB::table('answers')->where('answers.status', '>=', 1)->where(['answers.project' => 2]);
            $seminarsAns = DB::table('answers')->where('answers.status', '>=', 1)->where(['answers.project' => 2]);
            if ($request->region) {
                $region = DB::table('regions')->where('encoding', $request->region)->first() ?? abort(404);
                $data->where('answers.region', $region->id);
                $outreach->where('answers.region', $region->id);
                $volunteer->where('answers.region', $region->id);
                $duplicates->where('answers.region', $region->id);
                $noScan->where('answers.region', $region->id);
                $webinars->where('answers.region', $region->id);
                $seminars->where('answers.region', $region->id);
                $webinarsAns->where('answers.region', $region->id);
                $seminarsAns->where('answers.region', $region->id);
            }
        } else {
            $data = Answer::where('answers.status', '>=', 1)->where(['answers.region' => \Auth::user()->region->id, 'answers.project' => 2]);
            $outreach = Answer::where('answers.status', '>=', 1)->where(['answers.region' => \Auth::user()->region->id, 'answers.project' => 2]);
            $volunteer = Answer::where('answers.status', '>=', 1)->where(['answers.region' => \Auth::user()->region->id, 'answers.project' => 2]);
            $duplicates = Answer::where('answers.status', '>=', 1)->where(['answers.region' => \Auth::user()->region->id, 'answers.project' => 2]);
            $noScan = Answer::where('answers.status', '>=', 1)->where(['answers.region' => \Auth::user()->region->id, 'answers.project' => 2]);
            $webinars = Answer::where('answers.status', '>=', 1)->where(['answers.region' => \Auth::user()->region->id, 'answers.project' => 2]);
            $seminars = Answer::where('answers.status', '>=', 1)->where(['answers.region' => \Auth::user()->region->id, 'answers.project' => 2]);
            $webinarsAns = Answer::where('answers.status', '>=', 1)->where(['answers.region' => \Auth::user()->region->id, 'answers.project' => 2]);
            $seminarsAns = Answer::where('answers.status', '>=', 1)->where(['answers.region' => \Auth::user()->region->id, 'answers.project' => 2]);
        }

        if (isset($request->startDate) && isset($request->endDate)) {
            $data->whereBetween('date', [$request->startDate, $request->endDate]);
            $volunteer->whereBetween('date', [$request->startDate, $request->endDate]);
            $outreach->whereBetween('date', [$request->startDate, $request->endDate]);
            $duplicates->whereBetween('date', [$request->startDate, $request->endDate]);
            $noScan->whereBetween('date', [$request->startDate, $request->endDate]);
            $webinars->whereBetween('date', [$request->startDate, $request->endDate]);
            $seminars->whereBetween('date', [$request->startDate, $request->endDate]);
            $webinarsAns->whereBetween('date', [$request->startDate, $request->endDate]);
            $seminarsAns->whereBetween('date', [$request->startDate, $request->endDate]);
        }


        if ($request->encoding !== 'all') {
            $questionnaire = DB::table('questionnaires')->where('encoding', $request->encoding)->first() ?? abort(404);
            $data->where('answers.questionnaire', $questionnaire->id);
            $volunteer->where('answers.questionnaire', $questionnaire->id);
            $outreach->where('answers.questionnaire', $questionnaire->id);
            $duplicates->where('answers.questionnaire', $questionnaire->id);
            $noScan->where('answers.questionnaire', $questionnaire->id);
            $webinars->where('answers.questionnaire', $questionnaire->id);
            $seminars->where('answers.questionnaire', $questionnaire->id);
            $webinarsAns->where('answers.questionnaire', $questionnaire->id);
            $seminarsAns->where('answers.questionnaire', $questionnaire->id);
        }

        if (isset($request->filter) && $request->filter === 'duplicates') {
            $duplicates = $duplicates->select(DB::raw('GROUP_CONCAT(id) as ids'), 'region')->groupBy('region', 'questionnaire', 'type', 'outreach', 'volunteer', 'webinar')->having(DB::raw('COUNT(*)'), '>', 5)->get();
            $return_duplicates = '';
            foreach ($duplicates as $duplicate) {
                if ($request->region) {
                    !isset($return_duplicates[$duplicate->region]) ? $return_duplicates[$duplicate->region] = $duplicate->ids . ',' : $return_duplicates[$duplicate->region] .= $duplicate->ids . ',';
                } else {
                    $return_duplicates .= $duplicate->ids . ',';
                }
            }

            if ($request->region) {
                $data->whereIn('answers.id', explode(',', isset($return_duplicates[$region->id]) ? $return_duplicates[$region->id] : null))->orderBy('outreach');
            } else {
                $data->whereIn('answers.id', explode(',', $return_duplicates))->orderBy('outreach');
            }
        }

        $outreach = $outreach->groupBy('outreach')->get()->count();
        $volunteer = $volunteer->where('volunteer', '<>', '')->groupBy('volunteer')->get()->count();
        $noScan = $noScan->whereNull('scan')->count();

        $webinars = $webinars->where('answers.webinar', 1)->groupBy(['date'])->get()->count();
        $seminars = $seminars->whereNull('answers.webinar')->groupBy(['region', 'date', 'questionnaire'])->get()->count();
        $webinarsAns = $webinarsAns->where('answers.webinar', '=',1)->get()->count();
        $seminarsAns = $seminarsAns->whereNull('answers.webinar')->count();


        $data = $data->join('users as author', 'author.id', '=', 'answers.author')
            ->leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->leftJoin('questionnaires', 'questionnaires.id', '=', 'answers.questionnaire')
            ->select('answers.id', 'answers.date', 'answers.scan', 'regions.encoding as region', 'author.id as author_id', 'author.name_' . app()->getLocale() . ' as author', \DB::raw('CONCAT(outreaches.f_name, " ", outreaches.s_name) as outreach'), 'author.id as author_id', 'questionnaires.encoding as questionnaire', 'answers.type', 'answers.status')
            ->orderBy('answers.id', 'desc')
            ->paginate(10);

        $questionnaires = Questionnaire::where('project', 2)->get();


        $regions = Region::all();

        return view('pages.questionnaiares.view-questionnaires', ['webinarAns' => $webinarsAns, 'seminarAns' => $seminarsAns,'webinar' => $webinars, 'seminar' => $seminars, 'data' => $data, 'questionnaires' => $questionnaires, 'regions' => $regions, 'outreach' => $outreach, 'volunteer' => $volunteer, 'noScan' => $noScan]);
    }

    public function get(Request $request)
    {
        $data = DB::table('questionnaires')->where('questionnaires.encoding', $request->encoding)
            ->join('questions', 'questions.id_questionnaire', '=', 'questionnaires.id')
            ->join('variants_of_answers', 'variants_of_answers.id_question', '=', 'questions.id')
            ->select('questions.name_' . app()->getLocale() . ' as question', 'questions.id as id_question', 'questionnaires.name_' . app()->getLocale() . ' as questionnaire', 'questionnaires.id as id_questionnaire', 'variants_of_answers.id as id_answer', 'variants_of_answers.name_' . app()->getLocale() . ' as answer')
            ->get();

        foreach ($data as $array) {
            $questionnaire['name'] = $array->questionnaire;
            $questionnaire['id'] = $array->id_questionnaire;
            $questionnaire['encoding'] = $request->encoding;
            $questionnaire[$array->id_question]['question'] = $array->question;
            $questionnaire[$array->id_question]['answers'][$array->id_answer] = $array->answer;
        }

        $answers = DB::table('answers')->where('answers.id', $request->id);
        if ($answers->first()->author === \Auth::user()->id || \Auth::user()->role === 1) {
            $answers = $answers->join('regions', 'regions.id', '=', 'answers.region')
                    ->select('answers.*', 'regions.encoding as region', 'regions.id as id_region')->first() ?? abort(404);

            $answers->answers = json_decode($answers->answers, true);

            $outreaches = Outreach::where('region', $answers->id_region)->where('status', 1)->select(\DB::raw('CONCAT(f_name, " ", s_name) as name'), 'encoding', 'id')->orderBy('f_name')->get();

            return view('pages.questionnaiares.edit-questionnaires')->with(['questionnaire' => $questionnaire, 'outreaches' => $outreaches, 'answers' => $answers]);
        } else abort(403);
    }

    public function delete(Request $request)
    {
        $target = Answer::find($request->id);
        if (\Auth::user()->role === 1 || $target->author === \Auth::user()->id) Answer::where('id', $request->id)->update(['status' => 0]);
        else abort(403);

        $message = 'Анкета удалена!';
        return redirect()->back()->with('success', $message);
    }

    public function update(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            $check = explode('_', $key);
            if ($check[0] === $request->encoding) {
                $answers['answers'][$check[1]] = $value;
            }
        }
        $answers['author'] = \Auth::user()->id;
        $answers['questionnaire'] = $request->post('questionnaire');
        $answers['region'] = DB::table('regions')->where('encoding', $request->post('region'))->first()->id;
        $answers['type'] = $request->post('type');
        $answers['date'] = $request->post('date');
        $answers['outreach'] = $request->post('outreach');
        $answers['volunteer'] = $request->post('volunteer');
        $answers['answers'] = json_encode($answers['answers']);

        if (\Auth::user()->role === 1) {
            $table = Answer::whereId($request->id)->update($answers) ?? abort('404');
            $table->update($answers);
        } else {
            $table = Answer::where(['author' => \Auth::user()->id, 'id' => $request->id]) ?? abort('403');
            $table->update($answers);
        }

        $message = 'Анкета успешно изменена!';
        return redirect()->back()->with('success', $message);
    }

    public function set(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            $check = explode('_', $key);
            if ($check[0] === $request->encoding) {
                if (is_array($value)) {
                    foreach ($value as $val) {
                        $answers['answers'][$check[1]][] = $val;
                    }
                } else $answers['answers'][$check[1]] = $value;
            }
        }
        $answers['author'] = \Auth::user()->id;
        $answers['questionnaire'] = $request->post('questionnaire');
        $answers['region'] = is_numeric($request->post('region')) ? $request->post('region') : \Auth::user()->region->id;
        $answers['type'] = $request->post('type');
        $answers['date'] = $request->post('date');
        $answers['outreach'] = $request->post('outreach');
        $answers['volunteer'] = $request->post('volunteer');
        $answers['webinar'] = $request->post('webinar');
        $answers['client'] = $request->post('client');
        $answers['project'] = 2;
        Answer::create($answers);
        $message = 'Анкета успешно внесена в базу данных!';
        return redirect()->back()->with('success', $message);
    }

    public function opu_001(Request $request)
    {
        $drugs = TypesDrug::select('name_' . app()->getLocale() . ' as name', 'id')->get();
        $outreaches = Outreach::where('region', \Auth::user()->region->id === 0 ? 1 : \Auth::user()->region->id)->where('status', 1)->select(\DB::raw('CONCAT(f_name, " ", s_name) as name'), 'encoding', 'id')->orderBy('f_name')->get();
        $regionPosition = Position::whereProject(2)->whereRegion(\Auth::user()->region->id)->get();
        $i = 0;
        foreach ($regionPosition as $regionPositionData) {
            $staff[$i] = User::where('position', 'LIKE', '%"' . $regionPositionData->id . '"%')->where('status', '>=', 1)->first();
            $staff[$i]['positionName'] = $regionPositionData->name_ru;
            $i++;
        }
        $drop_inCenters = Drop_inCenter::where('region', \Auth::user()->region->id === 0 ? 1 : \Auth::user()->region->id)->select('id', 'encoding', 'name_' . app()->getLocale() . ' as name')->get();
        return view('pages.questionnaiares.questionnaire-opu_001', ['drop_inCenters' => $drop_inCenters, 'staff' => $staff, 'outreaches' => $outreaches, 'drugs' => $drugs]);
    }

    public function set_opu_001(Request $request)
    {
        $request->merge(['region' => \Auth::user()->region->id === 0 ? 1 : \Auth::user()->region->id]);
        QuestionnaireOPU_001::create(array_merge($request->except('_token'), ['author' => \Auth::user()->id]));
        $message = 'Анкета успешно внесена в базу данных!';
        return redirect()->back()->with('success', $message);
    }

    public function get_opu_001(Request $request)
    {
        $data = DB::table('questionnaire_o_p_u_001s')
            ->where('questionnaire_o_p_u_001s.id', $request->id)
            ->join('regions', 'regions.id', 'questionnaire_o_p_u_001s.region')
            ->join('drop_in_centers', 'drop_in_centers.id', 'questionnaire_o_p_u_001s.drop_inCenter')
            ->select('questionnaire_o_p_u_001s.*', 'regions.encoding as region', 'regions.id as id_region', 'questionnaire_o_p_u_001s.outreach', 'drug')
            ->first();
        if ($data->author === \Auth::user()->id || \Auth::user()->role === 1) {
            $drugs = TypesDrug::select('name_' . app()->getLocale() . ' as name', 'id')->get();
            $outreaches = Outreach::where('region', $data->region)->where('status', 1)->select(\DB::raw('CONCAT(f_name, " ", s_name) as name'), 'encoding', 'id')->orderBy('f_name')->get();
//                $staff          = User::join('positions', 'positions.id', 'users.position')->where('region', $data->region)->select('users.name_' . app()->getLocale() . ' as name', 'users.id as id', 'positions.name_' . app()->getLocale() . ' as position', 'positions.id as position_id')->get();
            $drop_inCenters = Drop_inCenter::where('region', $data->region)->select('id', 'encoding', 'name_' . app()->getLocale() . ' as name')->get();

            $regionPosition = Position::whereProject(2)->whereRegion($data->region)->get();

            foreach ($regionPosition as $regionPositionData) {
                $staff[$regionPositionData->id] = User::where('position', 'LIKE', '%' . $regionPositionData->id . '%')->where('status', '>=', 1)->first();
                $staff[$regionPositionData->id]['positionName'] = $regionPositionData->name_ru;
            }

            return view('pages.questionnaiares.edit-questionnaire-opu_001', ['drop_inCenters' => $drop_inCenters, 'staff' => $staff, 'outreaches' => $outreaches, 'drugs' => $drugs, 'data' => $data]);
        } else abort(403);
    }

    public function delete_opu_001(Request $request)
    {
        $target = QuestionnaireOPU_001::find($request->id);
        if (\Auth::user()->role === 1 || $target->author === \Auth::user()->id) QuestionnaireOPU_001::where('id', $request->id)->update(['status' => 0]);
        else abort(403);

        $message = 'Анкета удалена!';
        return redirect()->back()->with('success', $message);
    }

    public function update_opu_001(Request $request)
    {
        QuestionnaireOPU_001::find($request->id)->update($request->except('_token'));
        $message = 'Анкета успешно изменена!';
        return redirect()->back()->with('success', $message);
    }

    public function view_opu_001(Request $request)
    {
        if (\Auth::user()->region->id === 0) {
            $data = DB::table('questionnaire_o_p_u_001s')->where('questionnaire_o_p_u_001s.status', '>=', 1)->where(['questionnaire_o_p_u_001s.project' => 2]);
            $duplicates = DB::table('questionnaire_o_p_u_001s')->where('questionnaire_o_p_u_001s.status', '>=', 1)->where(['questionnaire_o_p_u_001s.project' => 2]);
            $noScan = DB::table('questionnaire_o_p_u_001s')->where('questionnaire_o_p_u_001s.status', '>=', 1)->where(['questionnaire_o_p_u_001s.project' => 2]);
            if ($request->region) {
                $region = DB::table('regions')->where('encoding', $request->region)->first() ?? abort(404);
                $data->where('questionnaire_o_p_u_001s.region', $region->id);
                $duplicates->where('questionnaire_o_p_u_001s.region', $region->id);
                $noScan->where('questionnaire_o_p_u_001s.region', $region->id);
            }
        } else {
            $data = QuestionnaireOPU_001::where('questionnaire_o_p_u_001s.status', '>=', 1)->where(['questionnaire_o_p_u_001s.region' => \Auth::user()->region->id, 'questionnaire_o_p_u_001s.project' => 2]);
            $duplicates = QuestionnaireOPU_001::where('questionnaire_o_p_u_001s.status', '>=', 1)->where(['questionnaire_o_p_u_001s.region' => \Auth::user()->region->id, 'questionnaire_o_p_u_001s.project' => 2]);
            $noScan = QuestionnaireOPU_001::where('questionnaire_o_p_u_001s.status', '>=', 1)->where(['questionnaire_o_p_u_001s.region' => \Auth::user()->region->id, 'questionnaire_o_p_u_001s.project' => 2]);
        }

        if (isset($request->startDate) && isset($request->endDate)) {
            $data->whereBetween('date', [$request->startDate, $request->endDate]);
            $duplicates->whereBetween('date', [$request->startDate, $request->endDate]);
            $noScan->whereBetween('date', [$request->startDate, $request->endDate]);
        }

        if (isset($request->filter) && $request->filter === 'duplicates') {
            $duplicates = $duplicates->select(DB::raw('GROUP_CONCAT(id) as ids'), 'region')->groupBy('author', 'drop_inCenter', 'encoding', 'region', 'region')->having(DB::raw('COUNT(*)'), '>', 1)->get();
            $return_duplicates = '';
            foreach ($duplicates as $duplicate) {
                if ($request->region) {
                    !isset($return_duplicates[$duplicate->region]) ? $return_duplicates[$duplicate->region] = $duplicate->ids . ',' : $return_duplicates[$duplicate->region] .= $duplicate->ids . ',';
                } else {
                    $return_duplicates .= $duplicate->ids . ',';
                }
            }

            if ($request->region) {
                $noScan->whereIn('questionnaire_o_p_u_001s.id', explode(',', isset($return_duplicates[$region->id]) ? $return_duplicates[$region->id] : null));
                $data->whereIn('questionnaire_o_p_u_001s.id', explode(',', isset($return_duplicates[$region->id]) ? $return_duplicates[$region->id] : null));
            } else {
                $noScan->whereIn('questionnaire_o_p_u_001s.id', explode(',', $return_duplicates));
                $data->whereIn('questionnaire_o_p_u_001s.id', explode(',', $return_duplicates));
            }
        }

        $noScan = $noScan->whereNull('scan')->count();

        $data = $data->join('users as author', 'author.id', '=', 'questionnaire_o_p_u_001s.author')
            ->join('regions', 'regions.id', '=', 'questionnaire_o_p_u_001s.region')
            ->join('drop_in_centers', 'drop_in_centers.id', '=', 'questionnaire_o_p_u_001s.drop_inCenter')
            ->join('outreaches', 'outreaches.id', '=', 'questionnaire_o_p_u_001s.outreach')
            ->select('outreaches.f_name', 'outreaches.s_name', 'questionnaire_o_p_u_001s.id', 'questionnaire_o_p_u_001s.scan', 'questionnaire_o_p_u_001s.date', 'regions.encoding as region', 'author.id as author_id', 'author.name_' . app()->getLocale() . ' as author', 'author.id as author_id', 'questionnaire_o_p_u_001s.encoding as encoding', 'drop_in_centers.encoding as drop_inCenter', 'questionnaire_o_p_u_001s.status')
            ->orderBy('id', 'desc')->paginate(10);


        $regions = Region::all();

        return view('pages.questionnaiares.view-questionnaire-opu_001', ['data' => $data, 'regions' => $regions, 'noScan' => $noScan]);
    }

    public function createQuestionnaire(Request $request)
    {
        return view('pages.library.registration.questionnaire');
    }

    public function createQuestionnaireSave(Request $request)
    {
        $arrays['encoding'] = 'AKB-' . $request->region . '-001';
        $arrays['project'] = 2;
        $arrays['name_ru'] = $request->name_ru;
        $arrays['author'] = auth()->user()->id;
        Questionnaire::create($arrays);
        return redirect()->back();
    }

}
