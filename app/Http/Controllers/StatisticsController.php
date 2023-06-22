<?php

namespace App\Http\Controllers;

use App\Answer;
use App\MioVisition;
use App\Outreach;
use App\Questionnaire;
use App\QuestionnaireOPU_001;
use App\Region;
use App\ReportProject2;
use App\TuberculosisOPT;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function clients(Request $request)
    {

        $filter = [
            $request->startDate ? $request->startDate : '2018-01-01',
            $request->endDate ? $request->endDate : '2018-01-01',
        ];


        if ($request->region === 'all') {
            $data = DB::table('questionnaire_o_p_u_001s')->where('status', '>=', 1)->where(['project' => 2]);
            $famale = DB::table('questionnaire_o_p_u_001s')->where('status', '>=', 1)->where(['project' => 2]);
            $male = DB::table('questionnaire_o_p_u_001s')->where('status', '>=', 1)->where(['project' => 2]);
            $outreaches = DB::table('questionnaire_o_p_u_001s')->where('status', '>=', 1)->where(['project' => 2]);
        } else {
            auth()->user()->region->encoding !== $request->region && auth()->user()->role === 4 ? abort('403') : '';
            $region = Region::where('encoding', $request->region)->first();
            $data = DB::table('questionnaire_o_p_u_001s')->where('status', '>=', 1)->where(['region' => $region->id, 'project' => 2]);
            $famale = DB::table('questionnaire_o_p_u_001s')->where('status', '>=', 1)->where(['region' => $region->id, 'project' => 2]);
            $male = DB::table('questionnaire_o_p_u_001s')->where('status', '>=', 1)->where(['region' => $region->id, 'project' => 2]);
            $outreaches = DB::table('questionnaire_o_p_u_001s')->where('status', '>=', 1)->where(['region' => $region->id, 'project' => 2]);
        }
        if (isset($request->startDate) && isset($request->endDate)) {
            $data->whereBetween('date', [$request->startDate, $request->endDate]);
            $famale->whereBetween('date', [$request->startDate, $request->endDate]);
            $male->whereBetween('date', [$request->startDate, $request->endDate]);
            $outreaches->whereBetween('date', [$request->startDate, $request->endDate]);
        }

        $famale = $famale->where('encoding', 'like', '____2__')->count();
        $male = $male->where('encoding', 'like', '____1__')->count();
        $outreaches = $outreaches->groupBy('outreach')->get()->count();

        $data = $data->get();


        for ($i = 1; $i < 12; $i++) {
            for ($j = 1; $j < 12; $j++) {
                $return[$i][$j] = 0;
                $return['answers'][$i][$j] = 0;
            }
        }

        $return['answers'][4]['5_1'] = 0;

        foreach ($data as $items) {
            foreach (json_decode($items->opu_001_0001) as $opu_001_0001) {
                $return['answers'][1][1] += $opu_001_0001 == 1 ? 1 : 0;
                $return['answers'][1][2] += $opu_001_0001 == 2 ? 1 : 0;
                $return['answers'][1][3] += $opu_001_0001 == 3 ? 1 : 0;
            }
            foreach (json_decode($items->opu_001_0002) as $opu_001_0002) {
                $return['answers'][2][1] += $opu_001_0002 == 1 ? 1 : 0;
                $return['answers'][2][2] += $opu_001_0002 == 2 ? 1 : 0;
                $return['answers'][2][3] += $opu_001_0002 == 3 ? 1 : 0;
                $return['answers'][2][4] += $opu_001_0002 == 4 ? 1 : 0;
            }

            $return['answers'][3][1] += $items->opu_001_0003_001 === 2 ? 1 : 0;
            $return['answers'][3][2] += $items->opu_001_0003_002 === 2 ? 1 : 0;
            $return['answers'][3][3] += $items->opu_001_0003_003 === 2 ? 1 : 0;
            $return['answers'][3][4] += $items->opu_001_0003_004 === 2 ? 1 : 0;
            $return['answers'][3][5] += $items->opu_001_0003_005 === 2 ? 1 : 0;
            $return['answers'][3][6] += $items->opu_001_0003_006 === 2 ? 1 : 0;
            $return['answers'][3][7] += $items->opu_001_0003_007 === 2 ? 1 : 0;
            $return['answers'][3][8] += $items->opu_001_0003_008 === 2 ? 1 : 0;
            $return['answers'][3][9] += $items->opu_001_0003_009 === 2 ? 1 : 0;

            $return['answers'][4][1] += $items->opu_001_0004_001 === 2 ? 1 : 0;
            $return['answers'][4][2] += $items->opu_001_0004_002 === 2 ? 1 : 0;
            $return['answers'][4][3] += $items->opu_001_0004_003 === 2 ? 1 : 0;
            $return['answers'][4][4] += $items->opu_001_0004_004 === 2 ? 1 : 0;
            $return['answers'][4][5] += $items->opu_001_0004_005 === 2 ? 1 : 0;
            $return['answers'][4]['5_1'] += $items->opu_001_0004_005 === 1 ? 1 : 0;
            $return['answers'][4][6] += $items->opu_001_0004_006 === 2 ? 1 : 0;
            $return['answers'][4][7] += $items->opu_001_0004_007 === 2 ? 1 : 0;

            $return['answers'][5][1] += $items->opu_001_0005_001 === 2 ? 1 : 0;
            $return['answers'][5][2] += $items->opu_001_0005_002 === 2 ? 1 : 0;
            $return['answers'][5][3] += $items->opu_001_0005_003 === 2 ? 1 : 0;
            $return['answers'][5][4] += $items->opu_001_0005_004 === 2 ? 1 : 0;
            $return['answers'][5][5] += $items->opu_001_0005_005 === 2 ? 1 : 0;

            $return[1][1] += $items->meetings_0 == $items->meetings_1 ? 1 : 0;
            $return[1][2] += $items->meetings_0 < $items->meetings_1 ? 1 : 0;
            $return[1][3] += $items->meetings_0 > $items->meetings_1 ? 1 : 0;
            $return[1][4] += $items->Syringes2Get < $items->Syringes2Want && $items->Syringes5Get < $items->Syringes5Want && $items->Syringes10Get < $items->Syringes10Want && $items->DoilyGet < $items->DoilyWant && $items->CondomsMGet < $items->CondomsMWant && $items->CondomsWGet < $items->CondomsWWant ? 1 : 0;

            for ($i = 1; $i <= 8; $i++) {
                $return[2][$i] += $items->drug === $i ? 1 : 0;
            }

            $return[3][1] += $items->Syringes10Get >= $items->Syringes10Want ? 1 : 0;
            $return[3][2] += $items->Syringes10Get < $items->Syringes10Want ? 1 : 0;
            $return[3][3] += ($items->Syringes10Get < $items->Syringes10Want ? $items->Syringes10Want : 0) + ($items->Syringes2Get < $items->Syringes2Want ? $items->Syringes2Want : 0) + ($items->Syringes5Get < $items->Syringes5Want ? $items->Syringes5Want : 0);
            $return[3][4] += ($items->Syringes10Get < $items->Syringes10Want ? 1 : 0) + ($items->Syringes2Get < $items->Syringes2Want ? 1 : 0) + ($items->Syringes5Get < $items->Syringes5Want ? 1 : 0);
            $return[3][5] += $items->Syringes10Get + $items->Syringes2Get + $items->Syringes5Get;

            $return[4][1] += $items->Syringes2Get >= $items->Syringes2Want ? 1 : 0;
            $return[4][2] += $items->Syringes2Get < $items->Syringes2Want ? 1 : 0;
            $return[4][3] += $items->Syringes2Get < $items->Syringes2Want ? $items->Syringes2Want : 0;
            $return[4][4] += $items->Syringes2Get < $items->Syringes2Want ? 1 : 0;
            $return[4][5] += $items->Syringes2Get;

            $return[5][1] += $items->Syringes5Get >= $items->Syringes5Want ? 1 : 0;
            $return[5][2] += $items->Syringes5Get < $items->Syringes5Want ? 1 : 0;
            $return[5][3] += $items->Syringes5Get < $items->Syringes5Want ? $items->Syringes5Want : 0;
            $return[5][4] += $items->Syringes5Get < $items->Syringes5Want ? 1 : 0;
            $return[5][5] += $items->Syringes5Get;

            $return[6][1] += $items->Syringes10Get >= $items->Syringes10Want ? 1 : 0;
            $return[6][2] += $items->Syringes10Get < $items->Syringes10Want ? 1 : 0;
            $return[6][3] += $items->Syringes10Get < $items->Syringes10Want ? $items->Syringes10Want : 0;
            $return[6][4] += $items->Syringes10Get < $items->Syringes10Want ? 1 : 0;
            $return[6][5] += $items->Syringes10Get;

            $return[7][1] += $items->DoilyGet >= $items->DoilyWant ? 1 : 0;
            $return[7][2] += $items->DoilyGet < $items->DoilyWant ? 1 : 0;
            $return[7][3] += $items->DoilyGet < $items->DoilyWant ? $items->DoilyWant : 0;
            $return[7][4] += $items->DoilyGet < $items->DoilyWant ? 1 : 0;
            $return[7][5] += $items->DoilyGet;

            $return[8][1] += $items->CondomsMGet !== 0 ? ($items->CondomsMGet >= $items->CondomsMWant ? 1 : 0) : 0;
            $return[8][2] += $items->CondomsMGet !== 0 ? ($items->CondomsMGet < $items->CondomsMWant ? 1 : 0) : 0;
            $return[8][3] += $items->CondomsMGet !== 0 ? ($items->CondomsMGet < $items->CondomsMWant ? $items->CondomsMWant : 0) : 0;
            $return[8][4] += $items->CondomsMGet !== 0 ? ($items->CondomsMGet < $items->CondomsMWant ? 1 : 0) : 0;
            $return[8][5] += $items->CondomsMGet;
            $return[8][6] += $items->CondomsMGet == 0 ? 1 : 0;

            $return[9][1] += $items->CondomsWGet !== 0 ? ($items->CondomsWGet >= $items->CondomsWWant ? 1 : 0) : 0;
            $return[9][2] += $items->CondomsWGet !== 0 ? ($items->CondomsWGet < $items->CondomsWWant ? 1 : 0) : 0;
            $return[9][3] += $items->CondomsWGet !== 0 ? ($items->CondomsWGet < $items->CondomsWWant ? $items->CondomsWWant : 0) : 0;
            $return[9][4] += $items->CondomsWGet !== 0 ? ($items->CondomsWGet < $items->CondomsWWant ? 1 : 0) : 0;
            $return[9][5] += $items->CondomsWGet;

            $return[10][1] += $items->PassHiv === 1 ? 1 : 0;
            $return[10][2] += $items->PassHiv === 2 ? 1 : 0;
            $return[10][3] += $items->PassHiv === 3 ? 1 : 0;
            $date_hiv = new Carbon($items->date_hiv ?? '1900-01-01');
            $now = new Carbon();
            $return[10][$date_hiv->year] += 1;
            $return[10][4] += $date_hiv->diffInMonths($now) <= 12 ? 1 : 0;
            $return[10][5] += $date_hiv->diffInMonths($now) > 12 ? 1 : 0;
            $return[10][6] += $items->OfferHiv;
            $return[10][7] += $items->EscortHiv;
            if ($items->OfferHiv === 1 && $items->EscortHiv === 1) $return[10][8] += 1;
            if ($items->OfferHiv === 0 && $items->EscortHiv === 0) $return[10][9] += 1;
            if ($items->OfferHiv === 0 && $items->EscortHiv === 0 && $date_hiv->diffInMonths($now) <= 12) $return[10][10] += 1;
            if ($items->OfferHiv === 0 && $items->EscortHiv === 0 && $date_hiv->diffInMonths($now) > 12) $return[10][11] += 1;

            $return[11][1] += $items->PassFluorography === 1 ? 1 : 0;
            $return[11][2] += $items->PassFluorography === 2 ? 1 : 0;
            $return[11][3] += $items->PassFluorography === 3 ? 1 : 0;
            $date_fluorography = new Carbon($items->date_fluorography ?? '1900-01-01');
            $now = new Carbon();
            $return[11][$date_fluorography->year] += 1;
            $return[11][4] += $date_fluorography->diffInMonths($now) <= 12 ? 1 : 0;
            $return[11][5] += $date_fluorography->diffInMonths($now) > 12 ? 1 : 0;
            $return[11][6] += $items->OfferFluorography;
            $return[11][7] += $items->EscortFluorography;

            $return[15][1] += $items->TbStatus === 2 ? 1 : 0;
            $return[15][2] += $items->TbStatus === 0 ? 1 : 0;
            $return[15][3] += ($items->TbDoc === 2 && $items->TbStatus === 2) ? 1 : 0;
            $return[15][4] += ($items->TbDoc === 1 && $items->TbStatus === 2) ? 1 : 0;
            $return[15][5] += $items->TbOut === 2 ? 1 : 0;
            $return[15][6] += $items->TbOut === 1 ? 1 : 0;
            $return[15][7] += ($items->TbOut === 2 && $items->TbRisk === 2) ? 1 : 0;
            $return[15][8] += ($items->TbOut === 2 && $items->TbRisk === 0) ? 1 : 0;
            $return[15][9] += ($items->TbOut === 2 && $items->TbRisk === 1) ? 1 : 0;

            if ($items->OfferFluorography === 1 && $items->EscortFluorography === 1) $return[11][8] += 1;
            if ($items->OfferFluorography === 0 && $items->EscortFluorography === 0) $return[11][9] += 1;
            if ($items->OfferFluorography === 0 && $items->EscortFluorography === 0 && $date_fluorography->diffInMonths($now) <= 12) $return[11][10] += 1;
            if ($items->OfferFluorography === 0 && $items->EscortFluorography === 0 && $date_fluorography->diffInMonths($now) > 12) $return[11][11] += 1;

            $return[12][1] += $items->RegistrationHiv === 1 ? 1 : 0;

            $start = new Carbon($filter[0]);
            $end = new Carbon($filter[1]);
            if ($date_fluorography >= $start && $date_fluorography <= $end) $return[13][2] += 1;
            else $return[13][2] += 0;
            if ($date_hiv >= $start && $date_hiv <= $end) $return[13][1] += 1;
            else $return[13][1] += 0;
//            if ($date_fluorography >= $start && $date_fluorography <= $end && $items->EscortHiv == 1) $return[13][2] += 1;
//            if ($date_hiv >= $start && $date_hiv <= $end && $items->EscortFluorography == 1) $return[13][1] += 1;
        }

        $return33 = ($return[4][3] + $return[5][3] + $return[6][3]) / (($return[4][4] + $return[5][4] + $return[6][4]) / 3);
        $return[3][3] = round($return33, 2);
        $return35 = ($return[4][5] + $return[5][5] + $return[6][5]) / $data->count();
        $return[3][5] = round($return35, 2);

        $regions = Region::all();

        foreach ($regions as $region) {
            $comment = QuestionnaireOPU_001::leftJoin('outreaches', 'outreaches.id', '=', 'questionnaire_o_p_u_001s.interviewer')->where('questionnaire_o_p_u_001s.region', $region->id)->where('questionnaire_o_p_u_001s.status', '>=', 1)->whereBetween('questionnaire_o_p_u_001s.date', $filter)
                ->select(DB::raw('questionnaire_o_p_u_001s.*, CONCAT(outreaches.f_name, " ", outreaches.s_name) as interviewer'))->get();
            foreach ($comment as $item) {
                $comments[$region->encoding]['Что не нравится в 2мг шприцах'][$item->interviewer][$item->encoding][] = $item->Syringes2NotLike;
                $comments[$region->encoding]['Что не нравится в 5мг шприцах'][$item->interviewer][$item->encoding][] = $item->Syringes5NotLike;
                $comments[$region->encoding]['Что не нравится в 10мг шприцах'][$item->interviewer][$item->encoding][] = $item->Syringes10NotLike;
                $comments[$region->encoding]['Что не нравится в салфетках'][$item->interviewer][$item->encoding][] = $item->DoilyNotLike;
                $comments[$region->encoding]['Что не нравится в мужских презервативах'][$item->interviewer][$item->encoding][] = $item->CondomsMNotLike;
                $comments[$region->encoding]['Что не нравится в женских презервативах'][$item->interviewer][$item->encoding][] = $item->CondomsWNotLike;
                $comments[$region->encoding]['Недостатки процедуры теста ВИЧ'][$item->interviewer][$item->encoding][] = $item->LimitationsHiv;
                $comments[$region->encoding]['Недостатки процедуры теста ТБ'][$item->interviewer][$item->encoding][] = $item->LimitationsFluorography;
                $comments[$region->encoding]['О чем разговаривают с аутричем'][$item->interviewer][$item->encoding][] = $item->TalkOutreach;
                $comments[$region->encoding]['Какие услуги хотят получать'][$item->interviewer][$item->encoding][] = $item->services;
            }
        }


        return view('pages.statistics.clients')->with([
            'outreaches' => $outreaches,
            'data' => $return,
            'regions' => $regions,
            'count' => $data->count(),
            'famale' => $famale,
            'male' => $male,
            'comments' => $comments
        ]);
    }

    public function outreaches(Request $request)
    {
        $filter = [
            $request->startDate ? $request->startDate : '2000-01-01',
            $request->endDate ? $request->endDate : '3000-12-31'
        ];

        if ($request->region === 'all') {
            $data = DB::table('answers')->where('answers.status', '>=', 1)->where(['answers.project' => 2]);
            $events = DB::table('answers')->where('answers.status', '>=', 1)->where(['answers.project' => 2]);
        } else {
            auth()->user()->region->encoding !== $request->region && auth()->user()->role === 4 ? abort('403') : '';
            $region = Region::where('encoding', $request->region)->first();
            $data = DB::table('answers')->where('answers.status', '>=', 1)->where(['answers.region' => $region->id, 'answers.project' => 2]);
            $events = DB::table('answers')->where('answers.status', '>=', 1)->where(['answers.region' => $region->id, 'answers.project' => 2]);
        }

        if (isset($request->startDate) && isset($request->endDate)) {
            $data->whereBetween('date', $filter);
            $events->whereBetween('date', $filter);
        }

        if ($request->questionnaire !== 'all') {
            $questionnaire = Questionnaire::where('encoding', $request->questionnaire)->first();
            $data->where('answers.questionnaire', $questionnaire->id);
            $events->where('answers.questionnaire', $questionnaire->id);
        }

        $events = $events->groupBy(['region', 'questionnaire', 'date'])->select(\DB::raw('COUNT(DISTINCT `date`) as count'), 'region', 'questionnaire', 'webinar')->get();

        foreach ($events as $event) {
            if (is_null($event->webinar)) {
                $return_events[$event->region][$event->questionnaire] += $event->count;
            }
            $return_eventsAll[$event->region] += $event->count;
        }

        $data = $data->join('questionnaires', 'questionnaires.id', 'answers.questionnaire')
            ->join('regions', 'regions.id', 'answers.region')
            ->join('outreaches', 'outreaches.id', 'answers.outreach')
            ->select('answers.id', 'questionnaires.encoding as questionnaire_encoding', 'questionnaire', 'regions.encoding as region', 'regions.id as id_region', 'type', 'date', \DB::raw('CONCAT(outreaches.f_name, " ", outreaches.s_name) as outreach'), 'volunteer', \DB::raw('GROUP_CONCAT(CONCAT(type, "/"), answers ORDER BY answers.type ASC SEPARATOR "/") as answers'))
            ->orderBy('answers.type', 'answers.region', 'answers.outreach', 'answers.volunteer')
            ->groupBy('answers.questionnaire', 'answers.region', 'answers.outreach', 'answers.volunteer')
            ->where('answers.status', '>=', 1)
            ->where(['answers.project' => 2]);
        if ($request->webinar === '1') $data = $data->where('answers.webinar', '=', $request->webinar);
        $data = $data->get();

        $data_correct_answers = DB::table('questions')->select('id', 'id_questionnaire', 'correct')->get();

        foreach ($data_correct_answers as $data_correct_answer) {
            $correct_answers[$data_correct_answer->id_questionnaire][$data_correct_answer->id] = count(explode(',', $data_correct_answer->correct)) == 1 ? $data_correct_answer->correct : explode(',', $data_correct_answer->correct);
        }

        foreach ($data as $datum) {
            $answers = explode('/', $datum->answers);
            if ($answers[0] == 1) {
                $datum->type1 = isset($answers[1]) ? count(array_intersect_assoc(json_decode($answers[1], true), $correct_answers[$datum->questionnaire])) : 'NULL';
                $datum->type2 = isset($answers[3]) ? count(array_intersect_assoc(json_decode($answers[3], true), $correct_answers[$datum->questionnaire])) : 'NULL';
            } elseif ($answers[0] == 2) {
                $datum->type1 = 'NULL';
                $datum->type2 = isset($answers[1]) ? count(array_intersect_assoc(json_decode($answers[1], true), $correct_answers[$datum->questionnaire])) : 'NULL';
            }
            $datum->count = count($correct_answers[$datum->questionnaire]);
        }

        $regions = Region::where('id', '<>', 0)->get();
        $questionnaires = Questionnaire::where('project', 2)->get();

        return view('pages.statistics.outreaches')->with([
            'regions' => $regions,
            'data' => $data,
            'questionnaires' => $questionnaires,
            'events' => isset($return_events) ? $return_events : 0,
            'return_eventsAll' => $return_eventsAll
        ]);
    }

    public function statistics(Request $request)
    {
        auth()->user()->role > 3 ? abort('403') : 0;

        $regions = Region::whereIn('id', [2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14])
            ->select('encoding')
            ->get();
        foreach ($regions as $region) {
            $mioRes[$region->encoding]['sum'] = 'N/A';
            $mioRes[$region->encoding]['N/A'] = 'N/A';
            $opuRes[$region->encoding]['sum'] = 'N/A';
            $opuRes[$region->encoding]['N/A'] = 'N/A';
            $questRes[$region->encoding]['sum'] = 'N/A';
            $questRes[$region->encoding]['N/A']['N/A'] = 'N/A';
        }

        $mioData = MioVisition::leftJoin('regions', 'regions.id', '=', 'mio_visitions.region');
        $mioData = $mioData->where(['mio_visitions.status' => 1, 'mio_visitions.project' => 2]);
        $request->get('startDate') ? $mioData = $mioData->whereBetween('datetime', [$request->get('startDate'), $request->get('endDate')]) : '';
        $mioData = $mioData->groupBy(DB::raw('MONTH(`datetime`)'), 'mio_visitions.region')
            ->select('regions.encoding as region', 'mio_visitions.datetime', DB::raw('COUNT(`mio_visitions`.`id`) as `count`'))
            ->orderBy('mio_visitions.region')
            ->orderBy('mio_visitions.datetime', 'ASC')
            ->get();
        $opuData = QuestionnaireOPU_001::leftJoin('regions', 'regions.id', '=', 'questionnaire_o_p_u_001s.region');
        $opuData = $opuData->where(['questionnaire_o_p_u_001s.status' => 1, 'questionnaire_o_p_u_001s.project' => 2]);
        $request->get('startDate') ? $opuData = $opuData->whereBetween('date', [$request->get('startDate'), $request->get('endDate')]) : '';
        $opuData = $opuData->groupBy(DB::raw('MONTH(`questionnaire_o_p_u_001s`.`date`)'), 'questionnaire_o_p_u_001s.region')
            ->select('regions.encoding as region', 'questionnaire_o_p_u_001s.date', DB::raw('COUNT(`questionnaire_o_p_u_001s`.`id`) as `count`'))
            ->orderBy('questionnaire_o_p_u_001s.region')
            ->orderBy('questionnaire_o_p_u_001s.date', 'ASC')
            ->get();

        $questData = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->leftJoin('questionnaires', 'questionnaires.id', '=', 'answers.questionnaire');

        $questData = $questData->where('answers.status', 1)->where('answers.project', 2)->where('answers.type', '>=', 2);
        $request->get('startDate') ? $questData = $questData->whereBetween('date', [$request->get('startDate'), $request->get('endDate')]) : '';
        $questData = $questData->groupBy(DB::raw('MONTH(`answers`.`date`)'), 'answers.region', 'answers.questionnaire')
            ->select('regions.encoding as region', 'answers.date', DB::raw('COUNT(DISTINCT CONCAT(`answers`.`outreach`, "-", COALESCE(`answers`.`volunteer`," "))) as `count`'), 'questionnaires.encoding as questionnaire')
            ->orderBy('answers.region')
            ->orderBy('answers.questionnaire', 'desc')
            ->orderBy('answers.date', 'ASC')
            ->get();


        $tmpRegion = 0;
        $i = 0;
        foreach ($mioData as $mioDatum) {
            $i++;
            unset($mioRes[$mioDatum->region]['N/A'], $mioRes[$mioDatum->region]['sum']);
            $mioRes[$mioDatum->region][$mioDatum->datetime->monthName . ' ' . $mioDatum->datetime->year] = $mioDatum->count;
            $tmpRegion !== $mioDatum->region && $tmpRegion !== 0 ? $mioRes[$tmpRegion]['sum'] = array_sum($mioRes[$tmpRegion]) : '';
            $tmpRegion = $mioDatum->region;
            $i === count($mioData) ? $mioRes[$tmpRegion]['sum'] = array_sum($mioRes[$tmpRegion]) : 0;
        }

        $tmpRegion = 0;
        $i = 0;
        foreach ($opuData as $opuDatum) {
            $i++;
            unset($opuRes[$opuDatum->region]['N/A'], $opuRes[$opuDatum->region]['sum']);
            $opuRes[$opuDatum->region][$opuDatum->date->monthName . ' ' . $opuDatum->date->year] = $opuDatum->count;
            $tmpRegion !== $opuDatum->region && $tmpRegion !== 0 ? $opuRes[$tmpRegion]['sum'] = array_sum($opuRes[$tmpRegion]) : '';
            $tmpRegion = $opuDatum->region;
            $i === count($opuData) ? $opuRes[$tmpRegion]['sum'] = array_sum($opuRes[$tmpRegion]) : 0;
        }

        $i = 0;
        foreach ($questData as $questDatum) {
            $i++;
            unset($questRes[$questDatum->region]['N/A'], $questRes[$questDatum->region]['sum']);
            $questRes[$questDatum->region][$questDatum->questionnaire][$questDatum->date->monthName . ' ' . $questDatum->date->year] = $questDatum->count;
            $tmpData[$questDatum->region] += $questDatum->count;
        }
        foreach ($questRes as $key => $value) {
            $questRes[$key]['sum'] = 0;
            foreach ($questRes[$key] as $ke => $val) {
                if ($ke !== 'sum' && $ke !== 'N/A') {
                    foreach ($questRes[$key][$ke] as $k => $v) {
                        $questRes[$key]['sum'] += $v;
                    }
                }
            }
        }

        return view('pages.statistics.statistics')->with(['mioRes' => $mioRes, 'opuRes' => $opuRes, 'questRes' => $questRes]);
    }

    public function outreachesAll(Request $request)
    {
        if($request->type === 'seminar') {
            $outreaches = Answer::leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
                ->leftJoin('regions', 'regions.id', '=', 'answers.region')
                ->leftJoin('outside_organizations', 'outside_organizations.id', '=', 'outreaches.organization')
                ->where('answers.status', '>=', 1)
                ->whereNull('answers.webinar')
                ->whereIn('outreaches.region', [2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14])
                ->whereBetween('answers.date', [$request->startDate ? $request->startDate : '2001-01-01', $request->endDate ? $request->endDate : '3000-01-01'])
                ->select(DB::raw('COUNT(DISTINCT `answers`.`date`) as `count`, CONCAT(`outreaches`.`f_name`, " ", `outreaches`.`s_name`) as outreach'), 'regions.encoding', 'answers.date', 'outreaches.assistant', 'outside_organizations.name as organization')
                ->groupBy('answers.outreach')
                ->orderBy('answers.region', 'asc')
                ->get();
        } elseif ($request->type === 'webinar') {
            $outreaches = Answer::leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
                ->leftJoin('regions', 'regions.id', '=', 'answers.region')
                ->leftJoin('outside_organizations', 'outside_organizations.id', '=', 'outreaches.organization')
                ->where('answers.status', '>=', 1)
                ->where('answers.webinar', '=', 1)
                ->whereIn('outreaches.region', [2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14])
                ->whereBetween('answers.date', [$request->startDate ? $request->startDate : '2001-01-01', $request->endDate ? $request->endDate : '3000-01-01'])
                ->select(DB::raw('COUNT(DISTINCT `answers`.`date`) as `count`, CONCAT(`outreaches`.`f_name`, " ", `outreaches`.`s_name`) as outreach'), 'regions.encoding', 'answers.date', 'outreaches.assistant', 'outside_organizations.name as organization')
                ->groupBy('answers.outreach')
                ->orderBy('answers.region', 'asc')
                ->get();
        } else {
            $outreaches = Answer::leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
                ->leftJoin('regions', 'regions.id', '=', 'answers.region')
                ->leftJoin('outside_organizations', 'outside_organizations.id', '=', 'outreaches.organization')
                ->where('answers.status', '>=', 1)
                ->whereIn('outreaches.region', [2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14])
                ->whereBetween('answers.date', [$request->startDate ? $request->startDate : '2001-01-01', $request->endDate ? $request->endDate : '3000-01-01'])
                ->select(DB::raw('COUNT(DISTINCT `answers`.`date`) as `count`, CONCAT(`outreaches`.`f_name`, " ", `outreaches`.`s_name`) as outreach'), 'regions.encoding', 'answers.date', 'outreaches.assistant', 'outside_organizations.name as organization')
                ->groupBy('answers.outreach')
                ->orderBy('answers.region', 'asc')
                ->get();
        }


        return view('pages.statistics.outreachesAll')->with(['outreaches' => $outreaches, 'type' => $request->type]);
    }

    public function outreachesClients(Request $request)
    {
        $statistics = QuestionnaireOPU_001::leftJoin('regions', 'regions.id', '=', 'questionnaire_o_p_u_001s.region')
            ->where('questionnaire_o_p_u_001s.status', '>=', 1)
            ->where('questionnaire_o_p_u_001s.project', 2)
            ->whereBetween('questionnaire_o_p_u_001s.date', [$request->startDate ? $request->startDate : '2001-01-01', $request->endDate ? $request->endDate : '3000-01-01'])
            ->select(DB::raw('COUNT(distinct `questionnaire_o_p_u_001s`.`outreach`) as `outreach`'), DB::raw('COUNT(distinct `questionnaire_o_p_u_001s`.`date`) as `countDate`'), DB::raw('COUNT(distinct `questionnaire_o_p_u_001s`.`encoding`) as `clients`'), 'regions.encoding as region')
            ->groupBy('questionnaire_o_p_u_001s.region')
            ->get();

        $outreaches = QuestionnaireOPU_001::leftJoin('outreaches', 'outreaches.id', '=', 'questionnaire_o_p_u_001s.outreach')
            ->leftJoin('regions', 'regions.id', '=', 'questionnaire_o_p_u_001s.region')
            ->where('questionnaire_o_p_u_001s.status', '>=', 1)
            ->where('questionnaire_o_p_u_001s.project', 2)
            ->whereBetween('questionnaire_o_p_u_001s.date', [$request->startDate ? $request->startDate : '2001-01-01', $request->endDate ? $request->endDate : '3000-01-01'])
            ->select(DB::raw('COUNT( DISTINCT `questionnaire_o_p_u_001s`.`encoding`) as `count`, CONCAT(`outreaches`.`f_name`, " ", `outreaches`.`s_name`) as outreach'), 'regions.encoding')
            ->groupBy('questionnaire_o_p_u_001s.outreach')
            ->orderBy('questionnaire_o_p_u_001s.region', 'asc')
            ->get();

        return view('pages.statistics.outreachesClients')->with(['outreaches' => $outreaches, 'statistics' => $statistics]);
    }

    public function mioVisitions(Request $request)
    {

        $mioVisitions = MioVisition::leftJoin('regions', 'regions.id', '=', 'mio_visitions.region')
            ->where('mio_visitions.status', '>=', 1)
            ->where('mio_visitions.project', 2)
            ->whereBetween('mio_visitions.datetime', [$request->startDate ? $request->startDate : '2001-01-01', $request->endDate ? $request->endDate : '3000-01-01'])
            ->select('mio_visitions.address', 'regions.encoding', 'mio_visitions.name', 'procurementSyringes2', 'procurementSyringes5', 'procurementSyringes10', 'procurementDoily', 'procurementCondomsM', 'procurementCondomsW', 'procurementHivBlood', 'procurementHivSpittle')
            ->orderBy('mio_visitions.region')
            ->get();

        return view('pages.statistics.mioVisitions')->with(['mioVisitions' => $mioVisitions]);
    }

    public function actions(Request $request)
    {
        $startDate = $request->startDate ? $request->startDate : '2001-01-01';
        $endDate = $request->endDate ? $request->endDate : '3001-01-01';

        $actionsWebinar = Answer::select(DB::raw('COUNT(DISTINCT `outreach`) as `outreach`, COUNT(DISTINCT `date`) as `actions`, COUNT(DISTINCT `volunteer`) as `volunteers`'))
            ->where('status', '>=', 1)
            ->where('answers.project', 2)
            ->where('webinar', 1)
            ->whereBetween('answers.date', [$request->startDate ? $request->startDate : '2001-01-01', $request->endDate ? $request->endDate : '3000-01-01'])
            ->first();

        $actionsWebinarAssistant = Answer::leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->where('answers.status', '>=', 1)
            ->where('outreaches.assistant', '>=', 1)
            ->where('answers.project', 2)
            ->where('webinar', 1)
            ->whereBetween('answers.date', [$request->startDate ? $request->startDate : '2001-01-01', $request->endDate ? $request->endDate : '3000-01-01'])
            ->groupBy('answers.outreach')
            ->get()->count();

        $actionsWebinarOutreach = Answer::leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->where('answers.status', '>=', 1)
            ->where('outreaches.assistant', '=', 0)
            ->where('answers.project', 2)
            ->where('webinar', 1)
            ->whereBetween('answers.date', [$request->startDate ? $request->startDate : '2001-01-01', $request->endDate ? $request->endDate : '3000-01-01'])
            ->groupBy('answers.outreach')
            ->get()->count();

        $actionsAll = Answer::select(DB::raw('COUNT(DISTINCT `outreach`) as `outreach`, COUNT(DISTINCT `volunteer`) as `volunteers`'))
            ->where('status', '>=', 1)
            ->where('answers.project', 2)
            ->whereBetween('answers.date', [$request->startDate ? $request->startDate : '2001-01-01', $request->endDate ? $request->endDate : '3000-01-01'])
            ->first();

        $actionsAllAssistant = Answer::leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->where('answers.status', '>=', 1)
            ->where('outreaches.assistant', '=', 1)
            ->where('answers.project', 2)
            ->whereBetween('answers.date', [$request->startDate ? $request->startDate : '2001-01-01', $request->endDate ? $request->endDate : '3000-01-01'])
            ->groupBy('answers.outreach')
            ->get()->count();

        $actionsAllOutreach = Answer::leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->where('answers.status', '>=', 1)
            ->where('outreaches.assistant', '=', 0)
            ->where('answers.project', 2)
            ->whereBetween('answers.date', [$request->startDate ? $request->startDate : '2001-01-01', $request->endDate ? $request->endDate : '3000-01-01'])
            ->groupBy('answers.outreach')
            ->get()->count();


        $actionsNoWebinarAssistant = Answer::leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->where('answers.status', '>=', 1)
            ->where('outreaches.assistant', '>=', 1)
            ->where('answers.project', 2)
            ->whereNull('webinar')
            ->whereBetween('answers.date', [$request->startDate ? $request->startDate : '2001-01-01', $request->endDate ? $request->endDate : '3000-01-01'])
            ->groupBy('answers.outreach')
            ->get()->count();

        $actionsNoWebinarOutreach = Answer::leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->where('answers.status', '>=', 1)
            ->where('outreaches.assistant', '=', 0)
            ->where('answers.project', 2)
            ->whereNull('webinar')
            ->whereBetween('answers.date', [$request->startDate ? $request->startDate : '2001-01-01', $request->endDate ? $request->endDate : '3000-01-01'])
            ->groupBy('answers.outreach')
            ->get()->count();

        $actionsNoWebinar = Answer::select(DB::raw('COUNT(DISTINCT `outreach`) as `outreach`, COUNT(DISTINCT `volunteer`) as `volunteers`'))
            ->where('status', '>=', 1)
            ->where('answers.project', 2)
            ->whereNull('webinar')
            ->whereBetween('answers.date', [$request->startDate ? $request->startDate : '2001-01-01', $request->endDate ? $request->endDate : '3000-01-01'])
            ->first();

        $events = DB::table('answers')
            ->where('answers.status', '>=', 1)
            ->where(['answers.project' => 2])
            ->whereBetween('date', [$request->startDate ? $request->startDate : '2001-01-01', $request->endDate ? $request->endDate : '3000-01-01'])
            ->whereNull('webinar')
            ->groupBy(['region', 'questionnaire', 'date'])
            ->select(\DB::raw('COUNT(DISTINCT `date`) as count'), 'region', 'questionnaire', 'webinar')
            ->get();

        foreach ($events as $event) {
            $return_eventsAll += $event->count;
        }

        $actionsNoWebinar->actions = $return_eventsAll;
        $actionsAll->actions = $return_eventsAll + $actionsWebinar->actions;

        $actionsThemes = Answer::leftJoin('questionnaires', 'questionnaires.id', '=', 'answers.questionnaire')
            ->leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->select(DB::raw("COUNT(IF(`outreaches`.`assistant` = '1', 1, NULL)) as `assistant`, COUNT(IF(`outreaches`.`assistant` = '0', 1, NULL)) as `outreach`, COUNT(DISTINCT `date`) as `actions`, COUNT(DISTINCT `volunteer`) as `volunteers`"), 'questionnaires.encoding', 'questionnaires.id')
            ->where('answers.status', '>=', 1)
            ->where('answers.project', 2)
            ->whereNull('webinar')
            ->groupBy(['answers.questionnaire',])
            ->orderBy('questionnaire')
            ->whereBetween('date', [$request->startDate ? $request->startDate : '2001-01-01', $request->endDate ? $request->endDate : '3000-01-01'])
            ->get();

        $actionsCountData = Answer::where('status', '>=', 1)->groupBy(['questionnaire', 'region', 'date'])->get();
        foreach ($actionsCountData as $item) {
            $actionsCount[$item->questionnaire] += 1;
        }

        $actionsRegionsAll = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->leftJoin('questionnaires', 'questionnaires.id', '=', 'answers.questionnaire')
            ->leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->where('answers.status', '>=', 1)
            ->where('answers.project', 2)
            ->whereBetween('date', [$request->startDate ? $request->startDate : '2001-01-01', $request->endDate ? $request->endDate : '3000-01-01'])
            ->groupBy(['answers.region', 'answers.questionnaire', 'answers.date'])
            ->orderBy('answers.date', 'ASC')
            ->orderBy('answers.region', 'ASC')
            ->orderBy('answers.questionnaire', 'ASC')
            ->select('answers.date', 'answers.webinar', 'questionnaires.encoding as questionnaire', 'regions.encoding as region', DB::raw("COUNT(IF(`outreaches`.`assistant` = '1', 1, NULL)) as `assistant`"), DB::raw("COUNT(IF(`outreaches`.`assistant` = '0', 1, NULL)) as `outreach`"), DB::raw('COUNT(DISTINCT `answers`.`volunteer`) as `volunteer`'))
            ->get();

        $actionsOutreachesAll = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->leftJoin('questionnaires', 'questionnaires.id', '=', 'answers.questionnaire')
            ->leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->where('answers.status', '>=', 1)
            ->where('answers.project', 2)
            ->where('answers.type','<=', 2)
            ->whereBetween('date', [$request->startDate ? $request->startDate : '2001-01-01', $request->endDate ? $request->endDate : '3000-01-01'])
            ->groupBy(['outreach'])
            ->orderBy('answers.date', 'ASC')
            ->orderBy('answers.region', 'ASC')
            ->orderBy('answers.questionnaire', 'ASC')
            ->select('answers.date', 'answers.webinar', 'questionnaires.encoding as questionnaire', 'regions.encoding as region', DB::raw("CONCAT(`outreaches`.`f_name`, ' ', `outreaches`.`s_name`) as `outreach`"))
            ->get();

        return view('pages.statistics.actions')->with([
            'actionsCount' => $actionsCount,
            'actionsAll' => $actionsAll,
            'actionsWebinar' => $actionsWebinar,
            'actionsNoWebinar' => $actionsNoWebinar,
            'actionsAllOutreach' => $actionsAllOutreach,
            'actionsWebinarOutreach' => $actionsWebinarOutreach,
            'actionsNoWebinarOutreach' => $actionsNoWebinarOutreach,
            'actionsAllAssistant' => $actionsAllAssistant,
            'actionsWebinarAssistant' => $actionsWebinarAssistant,
            'actionsNoWebinarAssistant' => $actionsNoWebinarAssistant,
            'actionsThemes' => $actionsThemes,
            'actionsRegionsAll' => $actionsRegionsAll,
            'actionsOutreachesAll' => $actionsOutreachesAll
        ]);
    }

    public function report(Request $request)
    {

        $filter = [
            $request->year . '-' . $request->month . '-01',
            $request->year . '-' . $request->month . '-31'
        ];

        if ($request->month == 'quarter1') $filter = [$request->year . '-01-01', $request->year . '-03-31'];
        if ($request->month == 'quarter2') $filter = [$request->year . '-04-01', $request->year . '-06-31'];
        if ($request->month == 'quarter3') $filter = [$request->year . '-07-01', $request->year . '-09-31'];
        if ($request->month == 'quarter4') $filter = [$request->year . '-10-01', $request->year . '-12-31'];
        if ($request->month == 'half1') $filter = [$request->year . '-01-01', $request->year . '-06-31'];
        if ($request->month == 'half2') $filter = [$request->year . '-07-01', $request->year . '-12-31'];
        if ($request->month == 'year') $filter = [$request->year . '-01-01', $request->year . '-12-31'];
        if ($request->month == 'project') $filter = ['2018-11-01', '2021-06-31'];

        $countQualityScan = QuestionnaireOPU_001::leftJoin('regions', 'regions.id', '=', 'questionnaire_o_p_u_001s.region')
            ->whereBetween('questionnaire_o_p_u_001s.date', $filter)
            ->where('questionnaire_o_p_u_001s.status', '>=', 1)
            ->where('questionnaire_o_p_u_001s.project', 2)
            ->whereNotNull('scan')
            ->groupBy('questionnaire_o_p_u_001s.region')
            ->select(DB::raw('COUNT(`questionnaire_o_p_u_001s`.`id`) as count'), 'regions.encoding', 'scan')
            ->get();

        $countQuality = QuestionnaireOPU_001::leftJoin('regions', 'regions.id', '=', 'questionnaire_o_p_u_001s.region')
            ->whereBetween('questionnaire_o_p_u_001s.date', $filter)
            ->where('questionnaire_o_p_u_001s.status', '>=', 1)
            ->where('questionnaire_o_p_u_001s.project', 2)
            ->whereNull('scan')
            ->groupBy('questionnaire_o_p_u_001s.region')
            ->select(DB::raw('COUNT(`questionnaire_o_p_u_001s`.`id`) as count'), 'regions.encoding', 'scan')
            ->get();

        $countQualityOutreaches = QuestionnaireOPU_001::leftJoin('regions', 'regions.id', '=', 'questionnaire_o_p_u_001s.region')
            ->whereBetween('questionnaire_o_p_u_001s.date', $filter)
            ->where('questionnaire_o_p_u_001s.status', '>=', 1)
            ->where('questionnaire_o_p_u_001s.project', 2)
            ->groupBy('questionnaire_o_p_u_001s.region')
            ->groupBy('questionnaire_o_p_u_001s.outreach')
            ->select(DB::raw('COUNT(`questionnaire_o_p_u_001s`.`id`) as count'), 'regions.encoding')
            ->get();

        $countAnswersWebinarOutreach = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('answers.project', 2)
            ->whereNotNull('answers.webinar')
            ->whereNull('answers.volunteer')
            ->where('outreaches.assistant', 0)
            ->where('outreaches.organization', '<>', 4)
            ->groupBy('answers.region')
            ->select(DB::raw('COUNT(DISTINCT `answers`.`outreach`) as count'), 'regions.encoding')
            ->get();

        $countAnswersWebinarAssistant = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('answers.project', 2)
            ->whereNotNull('answers.webinar')
            ->whereNull('answers.volunteer')
            ->where('outreaches.assistant', 1)
            ->groupBy('answers.region')
            ->select(DB::raw('COUNT(DISTINCT `answers`.`outreach`) as count'), 'regions.encoding')
            ->get();

        $countAnswersSeminarOutreach = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('answers.project', 2)
            ->whereNull('answers.webinar')
            ->whereNull('answers.volunteer')
            ->where('outreaches.assistant', 0)
            ->where('outreaches.organization', '<>', 4)
            ->groupBy('answers.region')
            ->select(DB::raw('COUNT(DISTINCT `answers`.`outreach`) as count'), 'regions.encoding')
            ->get();

        $countAnswersSeminarAssistant = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('answers.project', 2)
            ->whereNull('answers.webinar')
            ->whereNull('answers.volunteer')
            ->where('outreaches.assistant', 1)
            ->groupBy('answers.region')
            ->select(DB::raw('COUNT(DISTINCT `answers`.`outreach`) as count'), 'regions.encoding')
            ->get();


        $countAnswersWebinarVolunteer = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('answers.project', 2)
            ->whereNotNull('answers.webinar')
            ->whereNotNull('answers.volunteer')
            ->groupBy('answers.region')
            ->select(DB::raw('COUNT(DISTINCT `answers`.`volunteer`) as count'), 'regions.encoding')
            ->get();


        $countAnswersSeminarVolunteer = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('answers.project', 2)
            ->whereNull('answers.webinar')
            ->whereNotNull('answers.volunteer')
            ->groupBy('answers.region')
            ->select(DB::raw('COUNT(DISTINCT `answers`.`volunteer`) as count'), 'regions.encoding')
            ->get();

        $countAnswersSeminarHIV = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('answers.project', 2)
            ->whereNull('answers.webinar')
            ->whereNull('answers.volunteer')
            ->where('outreaches.organization', 4)
            ->groupBy('answers.region')
            ->select(DB::raw('COUNT(DISTINCT `answers`.`outreach`) as count'), 'regions.encoding')
            ->get();

        $countAnswersWebinarHIV = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('answers.project', 2)
            ->whereNotNull('answers.webinar')
            ->whereNull('answers.volunteer')
            ->where('outreaches.organization', 4)
            ->groupBy('answers.region')
            ->select(DB::raw('COUNT(DISTINCT `answers`.`outreach`) as count'), 'regions.encoding')
            ->get();

        $countAnswersScan = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('answers.project', 2)
            ->where('answers.type', '<=', 2)
            ->whereNotNull('scan')
            ->groupBy('answers.region')
            ->select(DB::raw('COUNT(`answers`.`id`) as count'), 'regions.encoding', 'scan')
            ->get();

        $countAnswers = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('answers.project', 2)
            ->where('answers.type', '<=', 2)
            ->whereNull('scan')
            ->groupBy('answers.region')
            ->select(DB::raw('COUNT(`answers`.`id`) as count'), 'regions.encoding', 'scan')
            ->get();


        $countVisitionsScan = MioVisition::leftJoin('regions', 'regions.id', '=', 'mio_visitions.region')
            ->whereBetween('mio_visitions.datetime', $filter)
            ->where('mio_visitions.status', '>=', 1)
            ->where('mio_visitions.project', 2)
            ->whereNotNull('scan')
            ->groupBy('mio_visitions.region')
            ->select(DB::raw('COUNT(`mio_visitions`.`id`) as count'), 'regions.encoding', 'scan')
            ->get();

        $countVisitions = MioVisition::leftJoin('regions', 'regions.id', '=', 'mio_visitions.region')
            ->whereBetween('mio_visitions.datetime', $filter)
            ->where('mio_visitions.status', '>=', 1)
            ->where('mio_visitions.project', 2)
            ->whereNull('scan')
            ->groupBy('mio_visitions.region')
            ->select(DB::raw('COUNT(`mio_visitions`.`id`) as count'), 'regions.encoding', 'scan')
            ->get();

        $events = Answer::where('answers.status', '>=', 1)
            ->where(['answers.project' => 2])
            ->whereBetween('date', $filter)
            ->groupBy(['region', 'questionnaire', 'date'])
            ->select(\DB::raw('COUNT(DISTINCT `date`) as count'), 'region', 'questionnaire', 'webinar')
            ->get();

        $uniqueOutreach = QuestionnaireOPU_001::whereBetween('questionnaire_o_p_u_001s.date', $filter)
            ->where('questionnaire_o_p_u_001s.status', '>=', 1)
            ->where('questionnaire_o_p_u_001s.project', 2)
            ->select(DB::raw('COUNT(DISTINCT `questionnaire_o_p_u_001s`.`outreach`) as count'))
            ->first()->count;

        $uniqueOutreachWebinar = Answer::leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('answers.project', 2)
            ->whereNotNull('answers.webinar')
            ->whereNull('answers.volunteer')
            ->where('outreaches.assistant', 0)
            ->select(DB::raw('COUNT(DISTINCT `answers`.`outreach`) as count'))
            ->first()->count;

        $uniqueAssistentWebinar = Answer::leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('answers.project', 2)
            ->whereNotNull('answers.webinar')
            ->whereNull('answers.volunteer')
            ->where('outreaches.assistant', 1)
            ->select(DB::raw('COUNT(DISTINCT `answers`.`outreach`) as count'))
            ->first()->count;

        $uniqueVolunteerWebinar = Answer::leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('answers.project', 2)
            ->whereNotNull('answers.webinar')
            ->whereNotNull('answers.volunteer')
            ->select(DB::raw('COUNT(DISTINCT `answers`.`volunteer`) as count'))
            ->first()->count;

        $uniqueOutreachSeminar = Answer::leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('answers.project', 2)
            ->whereNull('answers.webinar')
            ->whereNull('answers.volunteer')
            ->where('outreaches.assistant', 0)
            ->select(DB::raw('COUNT(DISTINCT `answers`.`outreach`) as count'))
            ->first()->count;

        $uniqueAssistentSeminar = Answer::leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('answers.project', 2)
            ->whereNull('answers.webinar')
            ->whereNull('answers.volunteer')
            ->where('outreaches.assistant', 1)
            ->select(DB::raw('COUNT(DISTINCT `answers`.`outreach`) as count'))
            ->first()->count;

        $uniqueVolunteerSeminar = Answer::leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('answers.project', 2)
            ->whereNull('answers.webinar')
            ->whereNotNull('answers.volunteer')
            ->select(DB::raw('COUNT(DISTINCT `answers`.`volunteer`) as count'))
            ->first()->count;

        $uniqueVisitions = MioVisition::whereBetween('mio_visitions.datetime', $filter)
            ->where('mio_visitions.status', '>=', 1)
            ->where('mio_visitions.project', 2)
            ->select(DB::raw('COUNT(DISTINCT `mio_visitions`.`address`, `mio_visitions`.`name`) as count'))
            ->first()->count;

        $BlockesAnswers = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('answers.project', 2)
            ->groupBy('answers.region')
            ->groupBy('answers.status')
            ->select('regions.encoding', 'answers.status', DB::raw('COUNT(`answers`.`id`) as `count`'))
            ->get();

        $BlockesOPU = QuestionnaireOPU_001::leftJoin('regions', 'regions.id', '=', 'questionnaire_o_p_u_001s.region')
            ->whereBetween('questionnaire_o_p_u_001s.date', $filter)
            ->where('questionnaire_o_p_u_001s.status', '>=', 1)
            ->where('questionnaire_o_p_u_001s.project', 2)
            ->groupBy('questionnaire_o_p_u_001s.region')
            ->groupBy('questionnaire_o_p_u_001s.status')
            ->select('regions.encoding', 'questionnaire_o_p_u_001s.status', DB::raw('COUNT(`questionnaire_o_p_u_001s`.`id`) as `count`'))
            ->get();

        $BlockesMio = MioVisition::leftJoin('regions', 'regions.id', '=', 'mio_visitions.region')
            ->whereBetween('mio_visitions.datetime', $filter)
            ->where('mio_visitions.status', '>=', 1)
            ->where('mio_visitions.project', 2)
            ->groupBy('mio_visitions.region')
            ->groupBy('mio_visitions.status')
            ->select('regions.encoding', 'mio_visitions.status', DB::raw('COUNT(`mio_visitions`.`id`) as `count`'))
            ->get();

        $uniqueHIVSeminar = Answer::leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('answers.project', 2)
            ->whereNull('answers.webinar')
            ->whereNull('answers.volunteer')
            ->where('outreaches.organization', 4)
            ->select(DB::raw('COUNT(DISTINCT `answers`.`outreach`) as count'))
            ->first()->count;

        $uniqueHIVWebinar = Answer::leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('answers.project', 2)
            ->whereNotNull('answers.webinar')
            ->whereNull('answers.volunteer')
            ->where('outreaches.organization', 4)
            ->select(DB::raw('COUNT(DISTINCT `answers`.`outreach`) as count'))
            ->first()->count;

        foreach ($events as $event) {
            if (is_null($event->webinar)) {
                $countSeminar[$event->region] += $event->count;
                $countSeminar['all'] += $event->count;
            } else {
                $countWebinar[$event->region] += $event->count;
                $countWebinar['all'] += $event->count;
            }
        }

        $regions = Region::whereIn('id', [2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14])->get();

        foreach ($BlockesAnswers as $item) {
            $Blockes[$item->encoding][$item->status] += $item->count;
        }

        foreach ($BlockesOPU as $item) {
            $Blockes[$item->encoding][$item->status] += $item->count;
        }

        foreach ($BlockesMio as $item) {
            $Blockes[$item->encoding][$item->status] += $item->count;
        }

        foreach ($regions as $region) {
            foreach ($countQuality as $item) {
                if ($region->encoding === $item->encoding) {
                    $countQualityReturn[$region->encoding]['noScan'] = $item->count;
                    $countQualityReturn[$region->encoding]['all'] = $item->count;
                }
            }

            foreach ($countQualityScan as $item) {
                if ($region->encoding === $item->encoding) {
                    $countQualityReturn[$region->encoding]['scan'] = $item->count;
                    $countQualityReturn[$region->encoding]['all'] = $item->count + $countQualityReturn[$region->encoding]['all'];
                    $countQualityReturn['all'] += $countQualityReturn[$region->encoding]['all'];
                }
            }

            foreach ($countQualityOutreaches as $item) {
                if ($region->encoding === $item->encoding) {
                    $countQualityOutreachesReturn[$region->encoding] += 1;
                    $countQualityOutreachesReturn['all'] += 1;
                }
            }

            foreach ($countAnswersWebinarOutreach as $item) {
                if ($region->encoding === $item->encoding) {
                    $countAnswersWebinarOutreachReturn[$region->encoding] = $item->count;
                    $countAnswersWebinarOutreachReturn['all'] += $item->count;
                }
            }

            foreach ($countAnswersSeminarOutreach as $item) {
                if ($region->encoding === $item->encoding) {
                    $countAnswersSeminarOutreachReturn[$region->encoding] = $item->count;
                    $countAnswersSeminarOutreachReturn['all'] += $item->count;
                }
            }

            foreach ($countAnswersWebinarAssistant as $item) {
                if ($region->encoding === $item->encoding) {
                    $countAnswersWebinarAssistantReturn[$region->encoding] = $item->count;
                    $countAnswersWebinarAssistantReturn['all'] += $item->count;
                }
            }

            foreach ($countAnswersSeminarAssistant as $item) {
                if ($region->encoding === $item->encoding) {
                    $countAnswersSeminarAssistantReturn[$region->encoding] = $item->count;
                    $countAnswersSeminarAssistantReturn['all'] += $item->count;
                }
            }

            foreach ($countAnswersWebinarHIV as $item) {
                if ($region->encoding === $item->encoding) {
                    $countAnswersWebinarHIVReturn[$region->encoding] = $item->count;
                    $countAnswersWebinarHIVReturn['all'] += $item->count;
                }
            }

            foreach ($countAnswersSeminarHIV as $item) {
                if ($region->encoding === $item->encoding) {
                    $countAnswersSeminarHIVReturn[$region->encoding] = $item->count;
                    $countAnswersSeminarHIVReturn['all'] += $item->count;
                }
            }

            foreach ($countAnswersWebinarVolunteer as $item) {
                if ($region->encoding === $item->encoding) {
                    $countAnswersWebinarVolunteerReturn[$region->encoding] = $item->count;
                    $countAnswersWebinarVolunteerReturn['all'] += $item->count;
                }
            }

            foreach ($countAnswersSeminarVolunteer as $item) {
                if ($region->encoding === $item->encoding) {
                    $countAnswersSeminarVolunteerReturn[$region->encoding] = $item->count;
                    $countAnswersSeminarVolunteerReturn['all'] += $item->count;
                }
            }

            foreach ($countAnswers as $item) {
                if ($region->encoding === $item->encoding) {
                    $countAnswersReturn[$region->encoding]['noScan'] = $item->count;
                    $countAnswersReturn[$region->encoding]['all'] = $item->count;
                }
            }

            foreach ($countAnswersScan as $item) {
                if ($region->encoding === $item->encoding) {
                    $countAnswersReturn[$region->encoding]['scan'] = $item->count;
                    $countAnswersReturn[$region->encoding]['all'] = $item->count + $countAnswersReturn[$region->encoding]['all'];
                    $countAnswersReturn['all'] += $countAnswersReturn[$region->encoding]['all'];
                }
            }

            foreach ($countVisitions as $item) {
                if ($region->encoding === $item->encoding) {
                    $countVisitionsReturn[$region->encoding]['noScan'] = $item->count;
                    $countVisitionsReturn[$region->encoding]['all'] = $item->count;
                }
            }

            foreach ($countVisitionsScan as $item) {
                if ($region->encoding === $item->encoding) {
                    $countVisitionsReturn[$region->encoding]['scan'] = $item->count;
                    $countVisitionsReturn[$region->encoding]['all'] = $item->count + $countVisitionsReturn[$region->encoding]['all'];
                    $countVisitionsReturn['all'] += $countVisitionsReturn[$region->encoding]['all'];
                }
            }

        }

        $saveData = ReportProject2::where('date', $request->year . '-' . $request->month . '-01')->where('status', '>=', 1)->get()->toArray();
        $saveDataReturn['all'] = [];
        foreach ($saveData as $id => $data) {
            $saveDataReturn[$data['region']] = $data;
            $saveDataReturn['all']['miovisitions'] += $data['miovisitions'];
            $saveDataReturn['all']['webinar'] += $data['webinar'];
            $saveDataReturn['all']['seminar'] += $data['seminar'];
            $saveDataReturn['all']['meetings'] += $data['meetings'];
            $saveDataReturn['all']['report_month'] += $data['report_month'];
            $saveDataReturn['all']['report'] += $data['report'];
        }

        return view('pages.statistics.report')->with(
            [
                'countQualityReturn' => $countQualityReturn,
                'countQualityOutreachesReturn' => $countQualityOutreachesReturn,
                'countAnswersWebinarOutreachReturn' => $countAnswersWebinarOutreachReturn,
                'countAnswersSeminarOutreachReturn' => $countAnswersSeminarOutreachReturn,
                'countAnswersWebinarAssistantReturn' => $countAnswersWebinarAssistantReturn,
                'countAnswersSeminarAssistantReturn' => $countAnswersSeminarAssistantReturn,
                'countAnswersReturn' => $countAnswersReturn,
                'countVisitionsReturn' => $countVisitionsReturn,
                'regions' => $regions,
                'saveDataReturn' => $saveDataReturn,
                'countAnswersWebinarVolunteerReturn' => $countAnswersWebinarVolunteerReturn,
                'countAnswersSeminarVolunteerReturn' => $countAnswersSeminarVolunteerReturn,
                'countSeminar' => $countSeminar,
                'countWebinar' => $countWebinar,
                'uniqueOutreachWebinar' => $uniqueOutreachWebinar,
                'uniqueAssistentWebinar' => $uniqueAssistentWebinar,
                'uniqueVolunteerWebinar' => $uniqueVolunteerWebinar,
                'uniqueOutreachSeminar' => $uniqueOutreachSeminar,
                'uniqueAssistentSeminar' => $uniqueAssistentSeminar,
                'uniqueVolunteerSeminar' => $uniqueVolunteerSeminar,
                'uniqueVisitions' => $uniqueVisitions,
                'uniqueOutreach' => $uniqueOutreach,
                'blockes' => $Blockes,
                'uniqueHIVSeminar' => $uniqueAssistentSeminar,
                'uniqueHIVWebinar' => $uniqueAssistentWebinar,
                'countAnswersWebinarHIVReturn' => $countAnswersWebinarHIVReturn,
                'countAnswersSeminarHIVReturn' => $countAnswersSeminarHIVReturn,
            ]
        );
    }

    public function reportBlock(Request $request)
    {
        Answer::where(['region' => $request->region, 'status' => 1])->whereBetween('date', [$request->year . '-' . $request->month . '-01', $request->year . '-' . $request->month . '-31'])->update(['status' => 2]);
        QuestionnaireOPU_001::where(['region' => $request->region, 'status' => 1])->whereBetween('date', [$request->year . '-' . $request->month . '-01', $request->year . '-' . $request->month . '-31'])->update(['status' => 2]);
        MioVisition::where(['region' => $request->region, 'status' => 1])->whereBetween('datetime', [$request->year . '-' . $request->month . '-01', $request->year . '-' . $request->month . '-31'])->update(['status' => 2]);

        return redirect()->back();
    }

    public function yearReport(Request $request)
    {
        $region = Region::whereEncoding($request->region)->first();

        $filter = [
            $request->year . '-01-01',
            $request->year . '-12-31'
        ];

        if ($request->month == 'quarter1') $filter = [$request->year . '-01-01', $request->year . '-03-31'];
        if ($request->month == 'quarter2') $filter = [$request->year . '-04-01', $request->year . '-06-31'];
        if ($request->month == 'quarter3') $filter = [$request->year . '-07-01', $request->year . '-09-31'];
        if ($request->month == 'quarter4') $filter = [$request->year . '-10-01', $request->year . '-12-31'];
        if ($request->month == 'half1') $filter = [$request->year . '-01-01', $request->year . '-06-31'];
        if ($request->month == 'half2') $filter = [$request->year . '-07-01', $request->year . '-12-31'];
        if ($request->month == 'year') $filter = [$request->year . '-01-01', $request->year . '-12-31'];

        $countQualityScan = QuestionnaireOPU_001::leftJoin('regions', 'regions.id', '=', 'questionnaire_o_p_u_001s.region')
            ->whereBetween('questionnaire_o_p_u_001s.date', $filter)
            ->where('questionnaire_o_p_u_001s.status', '>=', 1)
            ->where('regions.encoding', $region->encoding)
            ->where('questionnaire_o_p_u_001s.project', 2)
            ->whereNotNull('scan')
            ->groupBy(DB::raw('MONTH(`questionnaire_o_p_u_001s`.`date`)'))
            ->select(DB::raw('COUNT(`questionnaire_o_p_u_001s`.`id`) as count'), DB::raw('MONTH(`questionnaire_o_p_u_001s`.`date`) as `encoding`'), 'scan')
            ->get();

        $countQuality = QuestionnaireOPU_001::leftJoin('regions', 'regions.id', '=', 'questionnaire_o_p_u_001s.region')
            ->whereBetween('questionnaire_o_p_u_001s.date', $filter)
            ->where('questionnaire_o_p_u_001s.status', '>=', 1)
            ->where('regions.encoding', $region->encoding)
            ->where('questionnaire_o_p_u_001s.project', 2)
            ->whereNull('scan')
            ->groupBy(DB::raw('MONTH(`questionnaire_o_p_u_001s`.`date`)'))
            ->select(DB::raw('COUNT(`questionnaire_o_p_u_001s`.`id`) as count'), DB::raw('MONTH(`questionnaire_o_p_u_001s`.`date`) as `encoding`'), 'scan')
            ->get();

        $countQualityOutreaches = QuestionnaireOPU_001::leftJoin('regions', 'regions.id', '=', 'questionnaire_o_p_u_001s.region')
            ->whereBetween('questionnaire_o_p_u_001s.date', $filter)
            ->where('questionnaire_o_p_u_001s.status', '>=', 1)
            ->where('regions.encoding', $region->encoding)
            ->where('questionnaire_o_p_u_001s.project', 2)
            ->groupBy(DB::raw('MONTH(`questionnaire_o_p_u_001s`.`date`)'))
            ->groupBy('questionnaire_o_p_u_001s.outreach')
            ->select(DB::raw('COUNT(`questionnaire_o_p_u_001s`.`id`) as count'), DB::raw('MONTH(`questionnaire_o_p_u_001s`.`date`) as `encoding`'))
            ->get();

        $countAnswersWebinarOutreach = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('regions.encoding', $region->encoding)
            ->where('answers.project', 2)
            ->whereNotNull('answers.webinar')
            ->whereNull('answers.volunteer')
            ->where('outreaches.assistant', 0)
            ->groupBy(DB::raw('MONTH(`answers`.`date`)'))
            ->select(DB::raw('COUNT(DISTINCT `answers`.`outreach`) as count'), DB::raw('MONTH(`answers`.`date`) as `encoding`'))
            ->get();

        $countAnswersWebinarAssistant = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('regions.encoding', $region->encoding)
            ->where('answers.project', 2)
            ->whereNotNull('answers.webinar')
            ->whereNull('answers.volunteer')
            ->where('outreaches.assistant', 1)
            ->groupBy(DB::raw('MONTH(`answers`.`date`)'))
            ->select(DB::raw('COUNT(DISTINCT `answers`.`outreach`) as count'), DB::raw('MONTH(`answers`.`date`) as `encoding`'))
            ->get();

        $countAnswersSeminarOutreach = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('regions.encoding', $region->encoding)
            ->where('answers.project', 2)
            ->whereNull('answers.webinar')
            ->whereNull('answers.volunteer')
            ->where('outreaches.assistant', 0)
            ->groupBy(DB::raw('MONTH(`answers`.`date`)'))
            ->select(DB::raw('COUNT(DISTINCT `answers`.`outreach`) as count'), DB::raw('MONTH(`answers`.`date`) as `encoding`'))
            ->get();

        $countAnswersSeminarAssistant = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('regions.encoding', $region->encoding)
            ->where('answers.project', 2)
            ->whereNull('answers.webinar')
            ->whereNull('answers.volunteer')
            ->where('outreaches.assistant', 1)
            ->groupBy(DB::raw('MONTH(`answers`.`date`)'))
            ->select(DB::raw('COUNT(DISTINCT `answers`.`outreach`) as count'), DB::raw('MONTH(`answers`.`date`) as `encoding`'))
            ->get();

        $countAnswersWebinarVolunteer = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('regions.encoding', $region->encoding)
            ->where('answers.project', 2)
            ->whereNotNull('answers.webinar')
            ->whereNotNull('answers.volunteer')
            ->groupBy(DB::raw('MONTH(`answers`.`date`)'))
            ->select(DB::raw('COUNT(DISTINCT `answers`.`volunteer`) as count'), DB::raw('MONTH(`answers`.`date`) as `encoding`'))
            ->get();

        $countAnswersSeminarVolunteer = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('regions.encoding', $region->encoding)
            ->where('answers.project', 2)
            ->whereNull('answers.webinar')
            ->whereNotNull('answers.volunteer')
            ->groupBy(DB::raw('MONTH(`answers`.`date`)'))
            ->select(DB::raw('COUNT(DISTINCT `answers`.`volunteer`) as count'), DB::raw('MONTH(`answers`.`date`) as `encoding`'))
            ->get();

        $countAnswersScan = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('regions.encoding', $region->encoding)
            ->where('answers.project', 2)
            ->where('answers.type', '<=', 2)
            ->whereNotNull('scan')
            ->groupBy(DB::raw('MONTH(`answers`.`date`)'))
            ->select(DB::raw('COUNT(`answers`.`id`) as count'), DB::raw('MONTH(`answers`.`date`) as `encoding`'), 'scan')
            ->get();

        $countAnswers = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('regions.encoding', $region->encoding)
            ->where('answers.project', 2)
            ->where('answers.type', '<=', 2)
            ->whereNull('scan')
            ->groupBy(DB::raw('MONTH(`answers`.`date`)'))
            ->select(DB::raw('COUNT(`answers`.`id`) as count'), DB::raw('MONTH(`answers`.`date`) as `encoding`'), 'scan')
            ->get();


        $countVisitionsScan = MioVisition::leftJoin('regions', 'regions.id', '=', 'mio_visitions.region')
            ->whereBetween('mio_visitions.datetime', $filter)
            ->where('mio_visitions.status', '>=', 1)
            ->where('regions.encoding', $region->encoding)
            ->where('mio_visitions.project', 2)
            ->whereNotNull('scan')
            ->groupBy(DB::raw('MONTH(`mio_visitions`.`datetime`)'))
            ->select(DB::raw('COUNT(`mio_visitions`.`id`) as count'), DB::raw('MONTH(`mio_visitions`.`datetime`) as `encoding`'), 'scan')
            ->get();

        $countVisitions = MioVisition::leftJoin('regions', 'regions.id', '=', 'mio_visitions.region')
            ->whereBetween('mio_visitions.datetime', $filter)
            ->where('mio_visitions.status', '>=', 1)
            ->where('regions.encoding', $region->encoding)
            ->where('mio_visitions.project', 2)
            ->whereNull('scan')
            ->groupBy(DB::raw('MONTH(`mio_visitions`.`datetime`)'))
            ->select(DB::raw('COUNT(`mio_visitions`.`id`) as count'), DB::raw('MONTH(`mio_visitions`.`datetime`) as `encoding`'), 'scan')
            ->get();

        $events = Answer::where('answers.status', '>=', 1)
            ->where(['answers.project' => 2, 'answers.region' => $region->id])
            ->whereBetween('date', $filter)
            ->groupBy(['region', 'questionnaire', 'date'])
            ->select(\DB::raw('COUNT(DISTINCT `date`) as count'), DB::raw('MONTH(`answers`.`date`) as `region`'), 'questionnaire', 'webinar')
            ->get();

        foreach ($events as $event) {
            if (is_null($event->webinar)) $countSeminar[$event->region] += $event->count;
            else $countWebinar[$event->region] += $event->count;
        }

        $regions = [
            1 => 'Январь',
            2 => 'Февраль',
            3 => 'Март',
            4 => 'Апрель',
            5 => 'Май',
            6 => 'Июнь',
            7 => 'Июль',
            8 => 'Август',
            9 => 'Сентябрь',
            10 => 'Октябрь',
            11 => 'Ноябрь',
            12 => 'Декабрь',
        ];

        foreach ($regions as $id => $name) {
            foreach ($countQuality as $item) {
                if ($id === $item->encoding) {
                    $countQualityReturn[$name]['noScan'] = $item->count;
                    $countQualityReturn[$name]['all'] = $item->count;
                }
            }

            foreach ($countQualityScan as $item) {
                if ($id === $item->encoding) {
                    $countQualityReturn[$name]['scan'] = $item->count;
                    $countQualityReturn[$name]['all'] = $item->count + $countQualityReturn[$name]['all'];
                }
            }

            foreach ($countQualityOutreaches as $item) {
                if ($id === $item->encoding) $countQualityOutreachesReturn[$name] += 1;
            }

            foreach ($countAnswersWebinarOutreach as $item) {
                if ($id === $item->encoding) $countAnswersWebinarOutreachReturn[$name] = $item->count;
            }

            foreach ($countAnswersSeminarOutreach as $item) {
                if ($id === $item->encoding) $countAnswersSeminarOutreachReturn[$name] = $item->count;
            }

            foreach ($countAnswersWebinarAssistant as $item) {
                if ($id === $item->encoding) $countAnswersWebinarAssistantReturn[$name] = $item->count;
            }

            foreach ($countAnswersSeminarAssistant as $item) {
                if ($id === $item->encoding) $countAnswersSeminarAssistantReturn[$name] = $item->count;
            }

            foreach ($countAnswersWebinarVolunteer as $item) {
                if ($id === $item->encoding) $countAnswersWebinarVolunteerReturn[$name] = $item->count;
            }

            foreach ($countAnswersSeminarVolunteer as $item) {
                if ($id === $item->encoding) $countAnswersSeminarVolunteerReturn[$name] = $item->count;
            }

            foreach ($countAnswers as $item) {
                if ($id === $item->encoding) {
                    $countAnswersReturn[$name]['noScan'] = $item->count;
                    $countAnswersReturn[$name]['all'] = $item->count;
                }
            }

            foreach ($countAnswersScan as $item) {
                if ($id === $item->encoding) {
                    $countAnswersReturn[$name]['scan'] = $item->count;
                    $countAnswersReturn[$name]['all'] = $item->count + $countAnswersReturn[$name]['all'];
                }
            }

            foreach ($countVisitions as $item) {
                if ($id === $item->encoding) {
                    $countVisitionsReturn[$name]['noScan'] = $item->count;
                    $countVisitionsReturn[$name]['all'] = $item->count;
                }
            }

            foreach ($countVisitionsScan as $item) {
                if ($id === $item->encoding) {
                    $countVisitionsReturn[$name]['scan'] = $item->count;
                    $countVisitionsReturn[$name]['all'] = $item->count + $countVisitionsReturn[$name]['all'];
                }
            }

        }

        $saveData = ReportProject2::where('region', $region->id)->whereBetween('date', $filter)->select('report_project2s.*', DB::raw('MONTH(`date`) as `month`'))
            ->get()->toArray();

        foreach ($saveData as $id => $data) {
            $saveDataReturn[$data['month']] = $data;
        }

        $regionsData = Region::whereIn('id', [2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14])
            ->select('id', 'encoding')
            ->get();

        return view('pages.statistics.yearReport')->with(
            [
                'countQualityReturn' => $countQualityReturn,
                'countQualityOutreachesReturn' => $countQualityOutreachesReturn,
                'countAnswersWebinarOutreachReturn' => $countAnswersWebinarOutreachReturn,
                'countAnswersSeminarOutreachReturn' => $countAnswersSeminarOutreachReturn,
                'countAnswersWebinarAssistantReturn' => $countAnswersWebinarAssistantReturn,
                'countAnswersSeminarAssistantReturn' => $countAnswersSeminarAssistantReturn,
                'countAnswersReturn' => $countAnswersReturn,
                'countVisitionsReturn' => $countVisitionsReturn,
                'regions' => $regions,
                'regionsData' => $regionsData,
                'saveDataReturn' => $saveDataReturn,
                'countAnswersWebinarVolunteerReturn' => $countAnswersWebinarVolunteerReturn,
                'countAnswersSeminarVolunteerReturn' => $countAnswersSeminarVolunteerReturn,
                'countSeminar' => $countSeminar,
                'countWebinar' => $countWebinar
            ]
        );
    }

    public function programReport(Request $request)
    {
        $countAnswersOutreach = Answer::whereBetween('answers.date', [$request->start, $request->end])
            ->where('answers.status', '>=', 1)
            ->where('answers.project', 2)
            ->whereNull('answers.volunteer')
            ->whereIn('answers.region', [2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14])
            ->groupBy('outreach')
            ->get()->count();

        $countAnswersVolunteer = Answer::whereBetween('answers.date', [$request->start, $request->end])
            ->where('answers.status', '>=', 1)
            ->where('answers.project', 2)
            ->whereNotNull('answers.volunteer')
            ->select(DB::raw('DISTINCT `volunteer`'))
            ->get()->count();

        $countVisitions = MioVisition::whereBetween('datetime', [$request->start, $request->end])
            ->where('status', '>=', 1)
            ->where('project', 2)
            ->get()->count();


        $countSeminar = Answer::whereBetween('answers.date', [$request->start, $request->end])
            ->where('answers.status', '>=', 1)
            ->where('answers.project', 2)
            ->whereNull('answers.webinar')
            ->groupBy(['region', 'date'])
            ->get()->count();

        $countWebinar = Answer::whereBetween('answers.date', [$request->start, $request->end])
            ->where('answers.status', '>=', 1)
            ->where('answers.project', 2)
            ->whereNotNull('answers.webinar')
            ->select(DB::raw('DISTINCT `date`'))
            ->get()->count();

        $countOutreaches = QuestionnaireOPU_001::whereBetween('date', [$request->start, $request->end])
            ->where('status', '>=', 1)
            ->where('project', 2)
            ->select(DB::raw('DISTINCT `outreach`'))
            ->get()->count();

        $countOpu = QuestionnaireOPU_001::whereBetween('date', [$request->start, $request->end])
            ->where('status', '>=', 1)
            ->where('project', 2)
            ->get()->count();


        return view('pages.statistics.programReport')->with(
            [
                'countAnswersOutreach' => $countAnswersOutreach,
                'countAnswersVolunteer' => $countAnswersVolunteer,
                'countVisitions' => $countVisitions,
                'countSeminar' => $countSeminar,
                'countWebinar' => $countWebinar,
                'countOutreaches' => $countOutreaches,
                'countOpu' => $countOpu,
            ]
        );
    }

    public function report4Audit(Request $request)
    {

        $filter = [
            $request->year . '-' . $request->month . '-01',
            $request->year . '-' . $request->month . '-31'
        ];

        if ($request->month == 'quarter1') $filter = [$request->year . '-01-01', $request->year . '-03-31'];
        if ($request->month == 'quarter2') $filter = [$request->year . '-04-01', $request->year . '-06-31'];
        if ($request->month == 'quarter3') $filter = [$request->year . '-07-01', $request->year . '-09-31'];
        if ($request->month == 'quarter4') $filter = [$request->year . '-10-01', $request->year . '-12-31'];
        if ($request->month == 'half1') $filter = [$request->year . '-01-01', $request->year . '-06-31'];
        if ($request->month == 'half2') $filter = [$request->year . '-07-01', $request->year . '-12-31'];
        if ($request->month == 'year') $filter = [$request->year . '-01-01', $request->year . '-12-31'];

        $countQualityScan = QuestionnaireOPU_001::leftJoin('regions', 'regions.id', '=', 'questionnaire_o_p_u_001s.region')
            ->whereBetween('questionnaire_o_p_u_001s.date', $filter)
            ->where('questionnaire_o_p_u_001s.status', '>=', 1)
            ->where('questionnaire_o_p_u_001s.project', $request->project)
            ->whereNotNull('scan')
            ->groupBy('questionnaire_o_p_u_001s.region')
            ->select(DB::raw('COUNT(`questionnaire_o_p_u_001s`.`id`) as count'), 'regions.encoding', 'scan')
            ->get();

        $countQuality = QuestionnaireOPU_001::leftJoin('regions', 'regions.id', '=', 'questionnaire_o_p_u_001s.region')
            ->whereBetween('questionnaire_o_p_u_001s.date', $filter)
            ->where('questionnaire_o_p_u_001s.status', '>=', 1)
            ->where('questionnaire_o_p_u_001s.project', $request->project)
            ->whereNull('scan')
            ->groupBy('questionnaire_o_p_u_001s.region')
            ->select(DB::raw('COUNT(`questionnaire_o_p_u_001s`.`id`) as count'), 'regions.encoding', 'scan')
            ->get();

        $countQuality = QuestionnaireOPU_001::where('questionnaire_o_p_u_001s.status', '>=', 1)
            ->where('questionnaire_o_p_u_001s.project', $request->project)
            ->groupBy('questionnaire_o_p_u_001s.')
            ->get();

        $countQualityOutreaches = QuestionnaireOPU_001::leftJoin('regions', 'regions.id', '=', 'questionnaire_o_p_u_001s.region')
            ->whereBetween('questionnaire_o_p_u_001s.date', $filter)
            ->where('questionnaire_o_p_u_001s.status', '>=', 1)
            ->where('questionnaire_o_p_u_001s.project', $request->project)
            ->groupBy('questionnaire_o_p_u_001s.region')
            ->groupBy('questionnaire_o_p_u_001s.outreach')
            ->select(DB::raw('COUNT(`questionnaire_o_p_u_001s`.`id`) as count'), 'regions.encoding')
            ->get();

        $countAnswersWebinarOutreach = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('answers.project', $request->project)
            ->whereNotNull('answers.webinar')
            ->whereNull('answers.volunteer')
            ->where('outreaches.assistant', 0)
            ->groupBy('answers.region')
            ->select(DB::raw('COUNT(DISTINCT `answers`.`outreach`) as count'), 'regions.encoding')
            ->get();

        $countAnswersWebinarAssistant = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('answers.project', $request->project)
            ->whereNotNull('answers.webinar')
            ->whereNull('answers.volunteer')
            ->where('outreaches.assistant', 1)
            ->groupBy('answers.region')
            ->select(DB::raw('COUNT(DISTINCT `answers`.`outreach`) as count'), 'regions.encoding')
            ->get();

        $countAnswersSeminarOutreach = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('answers.project', $request->project)
            ->whereNull('answers.webinar')
            ->whereNull('answers.volunteer')
            ->where('outreaches.assistant', 0)
            ->groupBy('answers.region')
            ->select(DB::raw('COUNT(DISTINCT `answers`.`outreach`) as count'), 'regions.encoding')
            ->get();

        $countAnswersSeminarAssistant = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->leftJoin('outreaches', 'outreaches.id', '=', 'answers.outreach')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('answers.project', $request->project)
            ->whereNull('answers.webinar')
            ->whereNull('answers.volunteer')
            ->where('outreaches.assistant', 1)
            ->groupBy('answers.region')
            ->select(DB::raw('COUNT(DISTINCT `answers`.`outreach`) as count'), 'regions.encoding')
            ->get();

        $countAnswersWebinarVolunteer = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('answers.project', $request->project)
            ->whereNotNull('answers.webinar')
            ->whereNotNull('answers.volunteer')
            ->groupBy('answers.region')
            ->select(DB::raw('COUNT(DISTINCT `answers`.`volunteer`) as count'), 'regions.encoding')
            ->get();

        $countAnswersSeminarVolunteer = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('answers.project', $request->project)
            ->whereNull('answers.webinar')
            ->whereNotNull('answers.volunteer')
            ->groupBy('answers.region')
            ->select(DB::raw('COUNT(DISTINCT `answers`.`volunteer`) as count'), 'regions.encoding')
            ->get();

        $countAnswersScan = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('answers.project', $request->project)
            ->where('answers.type', '<=', 2)
            ->whereNotNull('scan')
            ->groupBy('answers.region')
            ->select(DB::raw('COUNT(`answers`.`id`) as count'), 'regions.encoding', 'scan')
            ->get();

        $countAnswers = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->whereBetween('answers.date', $filter)
            ->where('answers.status', '>=', 1)
            ->where('answers.project', $request->project)
            ->where('answers.type', '<=', 2)
            ->whereNull('scan')
            ->groupBy('answers.region')
            ->select(DB::raw('COUNT(`answers`.`id`) as count'), 'regions.encoding', 'scan')
            ->get();


        $countVisitionsScan = MioVisition::leftJoin('regions', 'regions.id', '=', 'mio_visitions.region')
            ->whereBetween('mio_visitions.datetime', $filter)
            ->where('mio_visitions.status', '>=', 1)
            ->where('mio_visitions.project', $request->project)
            ->whereNotNull('scan')
            ->groupBy('mio_visitions.region')
            ->select(DB::raw('COUNT(`mio_visitions`.`id`) as count'), 'regions.encoding', 'scan')
            ->get();

        $countVisitions = MioVisition::leftJoin('regions', 'regions.id', '=', 'mio_visitions.region')
            ->whereBetween('mio_visitions.datetime', $filter)
            ->where('mio_visitions.status', '>=', 1)
            ->where('mio_visitions.project', $request->project)
            ->whereNull('scan')
            ->groupBy('mio_visitions.region')
            ->select(DB::raw('COUNT(`mio_visitions`.`id`) as count'), 'regions.encoding', 'scan')
            ->get();

        if ($request->project == 1) $regions = Region::whereIn('id', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14])->get();
        if ($request->project == 2) $regions = Region::whereIn('id', [2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14])->get();

        foreach ($regions as $region) {
            foreach ($countQuality as $item) {
                if ($region->encoding === $item->encoding) {
                    $countQualityReturn[$region->encoding]['noScan'] = $item->count;
                    $countQualityReturn[$region->encoding]['all'] = $item->count;
                }
            }

            foreach ($countQualityScan as $item) {
                if ($region->encoding === $item->encoding) {
                    $countQualityReturn[$region->encoding]['scan'] = $item->count;
                    $countQualityReturn[$region->encoding]['all'] = $item->count + $countQualityReturn[$region->encoding]['all'];
                }
            }

            foreach ($countQualityOutreaches as $item) {
                if ($region->encoding === $item->encoding) $countQualityOutreachesReturn[$region->encoding] += 1;
            }

            foreach ($countAnswersWebinarOutreach as $item) {
                if ($region->encoding === $item->encoding) $countAnswersWebinarOutreachReturn[$region->encoding] = $item->count;
            }

            foreach ($countAnswersSeminarOutreach as $item) {
                if ($region->encoding === $item->encoding) $countAnswersSeminarOutreachReturn[$region->encoding] = $item->count;
            }

            foreach ($countAnswersWebinarAssistant as $item) {
                if ($region->encoding === $item->encoding) $countAnswersWebinarAssistantReturn[$region->encoding] = $item->count;
            }

            foreach ($countAnswersSeminarAssistant as $item) {
                if ($region->encoding === $item->encoding) $countAnswersSeminarAssistantReturn[$region->encoding] = $item->count;
            }

            foreach ($countAnswersWebinarVolunteer as $item) {
                if ($region->encoding === $item->encoding) $countAnswersWebinarVolunteerReturn[$region->encoding] = $item->count;
            }

            foreach ($countAnswersSeminarVolunteer as $item) {
                if ($region->encoding === $item->encoding) $countAnswersSeminarVolunteerReturn[$region->encoding] = $item->count;
            }

            foreach ($countAnswers as $item) {
                if ($region->encoding === $item->encoding) {
                    $countAnswersReturn[$region->encoding]['noScan'] = $item->count;
                    $countAnswersReturn[$region->encoding]['all'] = $item->count;
                }
            }

            foreach ($countAnswersScan as $item) {
                if ($region->encoding === $item->encoding) {
                    $countAnswersReturn[$region->encoding]['scan'] = $item->count;
                    $countAnswersReturn[$region->encoding]['all'] = $item->count + $countAnswersReturn[$region->encoding]['all'];
                }
            }

            foreach ($countVisitions as $item) {
                if ($region->encoding === $item->encoding) {
                    $countVisitionsReturn[$region->encoding]['noScan'] = $item->count;
                    $countVisitionsReturn[$region->encoding]['all'] = $item->count;
                }
            }

            foreach ($countVisitionsScan as $item) {
                if ($region->encoding === $item->encoding) {
                    $countVisitionsReturn[$region->encoding]['scan'] = $item->count;
                    $countVisitionsReturn[$region->encoding]['all'] = $item->count + $countVisitionsReturn[$region->encoding]['all'];
                }
            }

        }

        $saveData = ReportProject2::where('date', $request->year . '-' . $request->month . '-01')->get()->toArray();

        foreach ($saveData as $id => $data) {
            $saveDataReturn[$data['region']] = $data;
        }

        return view('pages.statistics.report4Audit')->with(
            [
                'countQualityReturn' => $countQualityReturn,
                'countQualityOutreachesReturn' => $countQualityOutreachesReturn,
                'countAnswersWebinarOutreachReturn' => $countAnswersWebinarOutreachReturn,
                'countAnswersSeminarOutreachReturn' => $countAnswersSeminarOutreachReturn,
                'countAnswersWebinarAssistantReturn' => $countAnswersWebinarAssistantReturn,
                'countAnswersSeminarAssistantReturn' => $countAnswersSeminarAssistantReturn,
                'countAnswersReturn' => $countAnswersReturn,
                'countVisitionsReturn' => $countVisitionsReturn,
                'regions' => $regions,
                'saveDataReturn' => $saveDataReturn,
                'countAnswersWebinarVolunteerReturn' => $countAnswersWebinarVolunteerReturn,
                'countAnswersSeminarVolunteerReturn' => $countAnswersSeminarVolunteerReturn
            ]
        );
    }

    public function mioVisitionsList(Request $request)
    {
        $filter = [
            $request->start ? $request->start : '2019-07-01',
            $request->end ? $request->end : '2019-09-31',
        ];

        $data = MioVisition::leftJoin('types_pharmacies', 'types_pharmacies.id', '=', 'mio_visitions.type')
            ->leftJoin('regions', 'regions.id', '=', 'mio_visitions.region')
            ->whereBetween('mio_visitions.datetime', $filter)
            ->whereIn('mio_visitions.region', [2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14])
            ->where('mio_visitions.status', '>=', 1)
            ->select(
                DB::raw('GROUP_CONCAT(DISTINCT `types_pharmacies`.`name_' . app()->getLocale() . '` SEPARATOR ", ") as types'),
                'regions.encoding as region',
                DB::raw('COUNT(`mio_visitions`.`id`) as count')
            )
            ->groupBy('region')
            ->get();

        return view('pages.statistics.mioVisitionsList')->with(
            [
                'data' => $data
            ]
        );
    }

    public function clients_APL(Request $request)
    {
        $filter = [
            $request->startDate ?? '2000-01-01',
            $request->endDate ?? '3000-01-01',
        ];

        $regions = Region::whereIn('id', [1, 3, 11])
            ->select('id', 'encoding')
            ->get();

        foreach ($regions as $region) {
            $regionsData[$region->encoding] = $region->id;
        }

        $questions = Questionnaire::leftJoin('questions', 'questions.id_questionnaire', '=', 'questionnaires.id')
            ->leftJoin('variants_of_answers', 'variants_of_answers.id_question', '=', 'questions.id')
            ->where('questionnaires.id', 10)
            ->select('questions.id as question_id', 'questions.name_ru as question', 'variants_of_answers.name_ru as answer', 'variants_of_answers.id as answer_id')
            ->get();

        foreach ($questions as $question) {
            $dataQuestions[$question->question_id]['name'] = $question->question;
            $dataQuestions[$question->question_id][$question->answer_id] = $question->answer;
        }

        $answers = Answer::where('questionnaire', '10')
            ->whereIn('region', $request->region === 'all' ? [1, 3, 11] : [$regionsData[$request->region]])
            ->whereBetween('date', $filter)
            ->where('answers.status', '>=', 1)
            ->get();

        foreach ($answers as $data) {
            foreach ($data->answers as $question => $answer) {
                $result[$question][$answer][$data->type] += 1;
            }
            $result['count']['all'] += 1;
            $result['count'][$data->type] += $data->type == 1 ? 1 : 0;
            $result['count'][$data->type] += $data->type == 2 ? 1 : 0;
        }

        return view('pages.statistics.clientsAPL')->with(
            [
                'questions' => $dataQuestions,
                'data' => $result,
                'regions' => $regions
            ]
        );

    }

    public function clients_OPZ(Request $request)
    {
        $filter = [
            $request->startDate ?? '2000-01-01',
            $request->endDate ?? '3000-01-01',
        ];

        $regions = Region::whereIn('id', [1, 3, 11])
            ->select('id', 'encoding')
            ->get();

        foreach ($regions as $region) {
            $regionsData[$region->encoding] = $region->id;
        }

        $answers = Answer::where('questionnaire', '9')
            ->whereIn('region', $request->region === 'all' ? [1, 3, 11] : [$regionsData[$request->region]])
            ->whereBetween('date', $filter)
            ->get();

        foreach ($answers as $answer) {
            $result['count']['pre'] += $answer->type === 1 ? 1 : 0;
            $result['count']['post'] += $answer->type === 2 ? 1 : 0;
            $result['count']['all'] += 1;
        }

        $data = Answer::join('questionnaires', 'questionnaires.id', 'answers.questionnaire')
            ->where('answers.status', '>=', 1)
            ->whereIn('answers.region', $request->region === 'all' ? [1, 3, 11] : [$regionsData[$request->region]])
            ->where(['answers.project' => 4])
            ->where(['questionnaires.id' => 9])
            ->orderBy('answers.date')
            ->get();

        foreach ($data as $datum) {
            $tempData[$datum->date->format('Y-m-d')][$datum->region][$datum->type][] = $datum->answers;
        }

        $data_correct_answers = DB::table('questions')->select('id', 'id_questionnaire', 'correct')->where('id_questionnaire', 9)->get();

        foreach ($data_correct_answers as $data_correct_answer) {
            $correct_answers[$data_correct_answer->id_questionnaire][$data_correct_answer->id] = count(explode(',', $data_correct_answer->correct)) == 1 ? $data_correct_answer->correct : explode(',', $data_correct_answer->correct);
        }

        foreach ($tempData as $date => $temp) {
            foreach ($temp as $region => $type) {
                foreach ($type as $typeNum => $datum) {
                    foreach ($datum as $answer) {
                        $result[$date][$region][$typeNum]['data'] += count(array_intersect_assoc($answer, $correct_answers[9]));
                        $result[$date][$region][$typeNum]['count'] += count($correct_answers[9]);
                        $result[$typeNum]['data'] += count(array_intersect_assoc($answer, $correct_answers[9]));
                        $result[$typeNum]['count'] += count($correct_answers[9]);
                        if ($region <> 3) $result[$date][$region][$typeNum]['clients'] = count($datum);
                    }
                }
            }
        }

        if ($request->region == '03-BU' || $request->region == 'all') {
            $result['2019-02-28'][3][1]['clients'] = 66;
            $result['2019-03-06'][3][1]['clients'] = 11;
            $result['2019-03-13'][3][1]['clients'] = 6;
            $result['2019-03-28'][3][1]['clients'] = 50;
            $result['2019-04-03'][3][1]['clients'] = 24;
            $result['2019-04-10'][3][1]['clients'] = 23;
            $result['2019-04-17'][3][1]['clients'] = 21;
            $result['2019-04-24'][3][1]['clients'] = 22;
            $result['2019-05-01'][3][1]['clients'] = 34;
            $result['2019-05-15'][3][1]['clients'] = 15;
            $result['2019-05-22'][3][1]['clients'] = 14;
            $result['2019-05-31'][3][1]['clients'] = 28;
            $result['2019-06-07'][3][1]['clients'] = 16;
            $result['2019-06-12'][3][1]['clients'] = 16;
            $result['2019-06-19'][3][1]['clients'] = 18;
            $result['2019-06-26'][3][1]['clients'] = 22;
            $result['2019-07-03'][3][1]['clients'] = 18;
            $result['2019-07-10'][3][1]['clients'] = 45;
            $result['2019-07-17'][3][1]['clients'] = 9;
            $result['2019-07-24'][3][1]['clients'] = 11;
            $result['2019-08-07'][3][1]['clients'] = 15;
            $result['2019-08-14'][3][1]['clients'] = 10;
            $result['2019-08-21'][3][1]['clients'] = 18;
            $result['2019-08-28'][3][1]['clients'] = 16;
            $result['2019-09-04'][3][1]['clients'] = 16;
            $result['2019-09-11'][3][1]['clients'] = 15;
            $result['2019-09-18'][3][1]['clients'] = 12;
            $result['2019-09-25'][3][1]['clients'] = 12;
            $result['2019-10-02'][3][1]['clients'] = 11;
            $result['2019-10-09'][3][1]['clients'] = 12;
            $result['2019-10-16'][3][1]['clients'] = 10;
            $result['2019-10-23'][3][1]['clients'] = 16;
            $result['2019-11-06'][3][1]['clients'] = 12;
            $result['2019-11-13'][3][1]['clients'] = 14;
            $result['2019-11-20'][3][1]['clients'] = 13;
            $result['2019-11-27'][3][1]['clients'] = 12;
            $result['2019-12-04'][3][1]['clients'] = 12;
            $result['2019-12-11'][3][1]['clients'] = 13;

            $result['2019-02-28'][3][2]['percent'] = '60.3';
            $result['2019-03-06'][3][2]['percent'] = '52.5';
            $result['2019-03-13'][3][2]['percent'] = '46.5';
            $result['2019-03-28'][3][2]['percent'] = '53.8';
            $result['2019-04-03'][3][2]['percent'] = '55';
            $result['2019-04-10'][3][2]['percent'] = '59.3';
            $result['2019-04-17'][3][2]['percent'] = '63';
            $result['2019-04-24'][3][2]['percent'] = '52.8';
            $result['2019-05-01'][3][2]['percent'] = '55.9';
            $result['2019-05-15'][3][2]['percent'] = '49.3';
            $result['2019-05-22'][3][2]['percent'] = '59.4';
            $result['2019-05-31'][3][2]['percent'] = '59.5';
            $result['2019-06-07'][3][2]['percent'] = '55.9';
            $result['2019-06-12'][3][2]['percent'] = '53.2';
            $result['2019-06-19'][3][2]['percent'] = '51.3';
            $result['2019-06-26'][3][2]['percent'] = '51.8';
            $result['2019-07-03'][3][2]['percent'] = '59.4';
            $result['2019-07-10'][3][2]['percent'] = '61.3';
            $result['2019-07-17'][3][2]['percent'] = '60.9';
            $result['2019-07-24'][3][2]['percent'] = '55.3';
            $result['2019-08-07'][3][2]['percent'] = '56';
            $result['2019-08-14'][3][2]['percent'] = '56.1';
            $result['2019-08-21'][3][2]['percent'] = '60.2';
            $result['2019-08-28'][3][2]['percent'] = '53.2';
            $result['2019-09-04'][3][2]['percent'] = '60.3';
            $result['2019-09-11'][3][2]['percent'] = '56.9';
            $result['2019-09-18'][3][2]['percent'] = '60.8';
            $result['2019-09-25'][3][2]['percent'] = '63.3';
            $result['2019-10-02'][3][2]['percent'] = '53.5';
            $result['2019-10-09'][3][2]['percent'] = '49.3';
            $result['2019-10-16'][3][2]['percent'] = '57';
            $result['2019-10-23'][3][2]['percent'] = '47';
            $result['2019-11-06'][3][2]['percent'] = '47.4';
            $result['2019-11-13'][3][2]['percent'] = '51.8';
            $result['2019-11-20'][3][2]['percent'] = '55.1';
            $result['2019-11-27'][3][2]['percent'] = '53.7';
            $result['2019-12-04'][3][2]['percent'] = '51.3';
            $result['2019-12-11'][3][2]['percent'] = '55';
        }
        if ($request->region == '11-TV' || $request->region == 'all') {
            $result['2019-01-10'][11][1]['clients'] = 4;
            $result['2019-02-07'][11][1]['clients'] = 20;
            $result['2019-02-14'][11][1]['clients'] = 20;
            $result['2019-02-28'][11][1]['clients'] = 20;
            $result['2019-03-28'][11][1]['clients'] = 50;
            $result['2019-07-11'][11][1]['clients'] = 4;
            $result['2019-08-22'][11][1]['clients'] = 10;
            $result['2019-09-12'][11][1]['clients'] = 52;
            $result['2019-09-26'][11][1]['clients'] = 16;
            $result['2019-10-10'][11][1]['clients'] = 14;
            $result['2019-10-17'][11][1]['clients'] = 10;
            $result['2019-10-24'][11][1]['clients'] = 8;
            $result['2019-11-14'][11][1]['clients'] = 13;
            $result['2019-12-05'][11][1]['clients'] = 17;

            $result['2019-01-10'][11][2]['percent'] = '48.4';
            $result['2019-02-07'][11][2]['percent'] = '39.8';
            $result['2019-02-14'][11][2]['percent'] = '48.7';
            $result['2019-02-28'][11][2]['percent'] = '45.5';
            $result['2019-03-28'][11][2]['percent'] = '52.4';
            $result['2019-07-11'][11][2]['percent'] = '39.4';
            $result['2019-08-22'][11][2]['percent'] = '49.7';
            $result['2019-09-12'][11][2]['percent'] = '56.4';
            $result['2019-09-26'][11][2]['percent'] = '40.8';
            $result['2019-10-10'][11][2]['percent'] = '48.4';
            $result['2019-10-17'][11][2]['percent'] = '37.8';
            $result['2019-10-24'][11][2]['percent'] = '41.4';
            $result['2019-11-14'][11][2]['percent'] = '39.5';
            $result['2019-12-05'][11][2]['percent'] = '40.9';

        }
        if ($request->region == '01-TA' || $request->region == 'all') {
            $result['2019-02-05'][1][1]['clients'] = 15;
            $result['2019-02-19'][1][1]['clients'] = 15;
            $result['2019-03-01'][1][1]['clients'] = 6;
            $result['2019-03-07'][1][1]['clients'] = 9;
            $result['2019-03-12'][1][1]['clients'] = 9;
            $result['2019-03-19'][1][1]['clients'] = 6;
            $result['2019-03-28'][1][1]['clients'] = 30;
            $result['2019-03-29'][1][1]['clients'] = 45;
            $result['2019-06-29'][1][1]['clients'] = 13;
            $result['2019-07-06'][1][1]['clients'] = 10;
            $result['2019-07-13'][1][1]['clients'] = 13;
            $result['2019-09-14'][1][1]['clients'] = 15;
            $result['2019-09-16'][1][1]['clients'] = 16;
            $result['2019-10-04'][1][1]['clients'] = 19;
            $result['2019-11-15'][1][1]['clients'] = 10;

            $result['2019-02-05'][1][2]['percent'] = '75.9';
            $result['2019-02-19'][1][2]['percent'] = '66.4';
            $result['2019-03-01'][1][2]['percent'] = '63.4';
            $result['2019-03-07'][1][2]['percent'] = '71';
            $result['2019-03-12'][1][2]['percent'] = '62.4';
            $result['2019-03-19'][1][2]['percent'] = '67.3';
            $result['2019-03-28'][1][2]['percent'] = '74.1';
            $result['2019-03-29'][1][2]['percent'] = '77.7';
            $result['2019-06-29'][1][2]['percent'] = '73.8';
            $result['2019-07-06'][1][2]['percent'] = '65.9';
            $result['2019-07-13'][1][2]['percent'] = '71.6';
            $result['2019-09-14'][1][2]['percent'] = '56.9';
            $result['2019-09-16'][1][2]['percent'] = '67.8';
            $result['2019-10-04'][1][2]['percent'] = '56.6';
            $result['2019-11-15'][1][2]['percent'] = '65.7';

        }


        return view('pages.statistics.clientsOPZ')->with(
            [
                'questions' => $dataQuestions,
                'data' => $result,
                'regions' => $regions
            ]
        );

    }

    public function clients_OPT(Request $request)
    {
        $filter = [
            $request->startDate ?? '2000-01-01',
            $request->endDate ?? '3000-01-01',
        ];

        $regions = Region::whereIn('id', [1, 3, 11])
            ->select('id', 'encoding')
            ->get();

        foreach ($regions as $region) {
            $regionsData[$region->encoding] = $region->id;
        }

        $data = TuberculosisOPT::leftJoin('users', 'users.id', '=', 'tuberculosis_o_p_ts.author')
            ->leftJoin('regions', 'regions.id', '=', 'tuberculosis_o_p_ts.region')
            ->leftJoin('prisons', 'prisons.id', '=', 'tuberculosis_o_p_ts.place')
            ->where('tuberculosis_o_p_ts.status', '>=', 1)
            ->whereBetween('tuberculosis_o_p_ts.date', $filter)
            ->whereIn('tuberculosis_o_p_ts.region', $request->region === 'all' ? [1, 3, 11] : [$regionsData[$request->region]])
            ->select('tuberculosis_o_p_ts.s_name', 'tuberculosis_o_p_ts.f_name', 'users.name_' . app()->getLocale() . ' as author', 'regions.encoding as region', 'tuberculosis_o_p_ts.id', 'prisons.encoding as place')
            ->get();

        $result['data'] = $data;

        $result['count'] = $data->count();

        $counts['sex'] = TuberculosisOPT::select('sex', DB::raw('count(`sex`) as `count`'))->whereIn('tuberculosis_o_p_ts.region', $request->region === 'all' ? [1, 3, 11] : [$regionsData[$request->region]])->where('status', '>=', 1)->whereBetween('tuberculosis_o_p_ts.date', $filter)->groupBy('sex')->get();
        $counts['have_home'] = TuberculosisOPT::select('have_home', DB::raw('count(`have_home`) as `count`'))->whereIn('tuberculosis_o_p_ts.region', $request->region === 'all' ? [1, 3, 11] : [$regionsData[$request->region]])->where('status', '>=', 1)->whereBetween('tuberculosis_o_p_ts.date', $filter)->groupBy('have_home')->get();
        $counts['problem_registration'] = TuberculosisOPT::select('problem_registration', DB::raw('count(`problem_registration`) as `count`'))->whereIn('tuberculosis_o_p_ts.region', $request->region === 'all' ? [1, 3, 11] : [$regionsData[$request->region]])->where('status', '>=', 1)->whereBetween('tuberculosis_o_p_ts.date', $filter)->groupBy('problem_registration')->get();
        $counts['problem_state'] = TuberculosisOPT::select('problem_state', DB::raw('count(`problem_state`) as `count`'))->whereIn('tuberculosis_o_p_ts.region', $request->region === 'all' ? [1, 3, 11] : [$regionsData[$request->region]])->where('status', '>=', 1)->whereBetween('tuberculosis_o_p_ts.date', $filter)->groupBy('problem_state')->get();
        $countsTemp['status_passport'] = TuberculosisOPT::select('status_passport')->whereIn('tuberculosis_o_p_ts.region', $request->region === 'all' ? [1, 3, 11] : [$regionsData[$request->region]])->where('status', '>=', 1)->whereBetween('tuberculosis_o_p_ts.date', $filter)->get();
        $countsTemp['problems'] = TuberculosisOPT::select('problems', DB::raw('count(`problems`) as `count`'))->whereIn('tuberculosis_o_p_ts.region', $request->region === 'all' ? [1, 3, 11] : [$regionsData[$request->region]])->where('status', '>=', 1)->whereBetween('tuberculosis_o_p_ts.date', $filter)->groupBy('problems')->get();
        foreach ($countsTemp['status_passport'] as $count) {
            foreach ($count->status_passport as $item) {
                $counts['status_passport'][$item] += 1;
            }
        }
        foreach ($countsTemp['problems'] as $count) {
            foreach ($count->problems as $item) {
                $item === "8" ? $counts['problems'][7] += 1 : $counts['problems'][$item] += 1;
            }
        }

        $counts['want_education'] = TuberculosisOPT::select('want_education', DB::raw('count(`want_education`) as `count`'))->whereIn('tuberculosis_o_p_ts.region', $request->region === 'all' ? [1, 3, 11] : [$regionsData[$request->region]])->where('status', '>=', 1)->whereBetween('tuberculosis_o_p_ts.date', $filter)->groupBy('want_education')->get();
        $counts['relationships'] = TuberculosisOPT::select('relationships', DB::raw('count(`relationships`) as `count`'))->whereIn('tuberculosis_o_p_ts.region', $request->region === 'all' ? [1, 3, 11] : [$regionsData[$request->region]])->where('status', '>=', 1)->whereBetween('tuberculosis_o_p_ts.date', $filter)->groupBy('relationships')->get();
        $counts['have_family'] = TuberculosisOPT::select('have_family', DB::raw('count(`have_family`) as `count`'))->whereIn('tuberculosis_o_p_ts.region', $request->region === 'all' ? [1, 3, 11] : [$regionsData[$request->region]])->where('status', '>=', 1)->whereBetween('tuberculosis_o_p_ts.date', $filter)->groupBy('have_family')->get();
        $counts['hiv'] = TuberculosisOPT::select('hiv', DB::raw('count(`hiv`) as `count`'))->whereIn('tuberculosis_o_p_ts.region', $request->region === 'all' ? [1, 3, 11] : [$regionsData[$request->region]])->where('status', '>=', 1)->whereBetween('tuberculosis_o_p_ts.date', $filter)->groupBy('hiv')->get();
        $counts['addiction'] = TuberculosisOPT::select('addiction', DB::raw('count(`addiction`) as `count`'))->whereIn('tuberculosis_o_p_ts.region', $request->region === 'all' ? [1, 3, 11] : [$regionsData[$request->region]])->where('status', '>=', 1)->whereBetween('tuberculosis_o_p_ts.date', $filter)->groupBy('addiction')->get();
        $counts['help_addiction'] = TuberculosisOPT::select('help_addiction', DB::raw('count(`help_addiction`) as `count`'))->whereIn('tuberculosis_o_p_ts.region', $request->region === 'all' ? [1, 3, 11] : [$regionsData[$request->region]])->where('status', '>=', 1)->whereBetween('tuberculosis_o_p_ts.date', $filter)->groupBy('help_addiction')->get();
        $counts['help_disability'] = TuberculosisOPT::select('help_disability', DB::raw('count(`help_disability`) as `count`'))->whereIn('tuberculosis_o_p_ts.region', $request->region === 'all' ? [1, 3, 11] : [$regionsData[$request->region]])->where('status', '>=', 1)->whereBetween('tuberculosis_o_p_ts.date', $filter)->groupBy('help_disability')->get();
        $countsTemp['emotions'] = TuberculosisOPT::select('emotions', DB::raw('count(`emotions`) as `count`'))->whereIn('tuberculosis_o_p_ts.region', $request->region === 'all' ? [1, 3, 11] : [$regionsData[$request->region]])->where('status', '>=', 1)->whereBetween('tuberculosis_o_p_ts.date', $filter)->groupBy('emotions')->get();
        foreach ($countsTemp['emotions'] as $count) {
            foreach ($count->emotions as $item) {
                $counts['emotions'][$item] += 1;
            }
        }
        $counts['return_job'] = TuberculosisOPT::select('return_job', DB::raw('count(`return_job`) as `count`'))->whereIn('tuberculosis_o_p_ts.region', $request->region === 'all' ? [1, 3, 11] : [$regionsData[$request->region]])->where('status', '>=', 1)->whereBetween('tuberculosis_o_p_ts.date', $filter)->groupBy('return_job')->get();
        $counts['status_job'] = TuberculosisOPT::select('status_job', DB::raw('count(`status_job`) as `count`'))->whereIn('tuberculosis_o_p_ts.region', $request->region === 'all' ? [1, 3, 11] : [$regionsData[$request->region]])->where('status', '>=', 1)->whereBetween('tuberculosis_o_p_ts.date', $filter)->groupBy('status_job')->get();
        $counts['plans'] = TuberculosisOPT::select('plans', DB::raw('count(`plans`) as `count`'))->whereIn('tuberculosis_o_p_ts.region', $request->region === 'all' ? [1, 3, 11] : [$regionsData[$request->region]])->where('status', '>=', 1)->whereBetween('tuberculosis_o_p_ts.date', $filter)->groupBy('plans')->get();
        $counts['lawyer'] = TuberculosisOPT::select('lawyer', DB::raw('count(`lawyer`) as `count`'))->whereIn('tuberculosis_o_p_ts.region', $request->region === 'all' ? [1, 3, 11] : [$regionsData[$request->region]])->where('status', '>=', 1)->whereBetween('tuberculosis_o_p_ts.date', $filter)->groupBy('lawyer')->get();
        $counts['psychologist'] = TuberculosisOPT::select('psychologist', DB::raw('count(`psychologist`) as `count`'))->whereIn('tuberculosis_o_p_ts.region', $request->region === 'all' ? [1, 3, 11] : [$regionsData[$request->region]])->where('status', '>=', 1)->whereBetween('tuberculosis_o_p_ts.date', $filter)->groupBy('psychologist')->get();
        $counts['social'] = TuberculosisOPT::select('social', DB::raw('count(`social`) as `count`'))->whereIn('tuberculosis_o_p_ts.region', $request->region === 'all' ? [1, 3, 11] : [$regionsData[$request->region]])->where('status', '>=', 1)->whereBetween('tuberculosis_o_p_ts.date', $filter)->groupBy('social')->get();


//            dd($counts['problems']);

        if ($request->region === 'all' || $request->region === '03-BU') {
            $counts['status_passport'][1] += round(($result['count'] / 100 * 34.58), 0);
            $counts['status_passport'][3] += round(($result['count'] / 100 * 11.68), 0);
            $counts['status_passport'][5] += round(($result['count'] / 100 * 11.68), 0);

            $counts['emotions'][1] += round(($result['count'] / 100 * 8.41), 0);
            $counts['emotions'][2] += round(($result['count'] / 100 * 6.54), 0);
            $counts['emotions'][3] += round(($result['count'] / 100 * 5.61), 0);
            $counts['emotions'][4] += round(($result['count'] / 100 * 6.07), 0);
            $counts['emotions'][5] += round(($result['count'] / 100 * 8.41), 0);
            $counts['emotions'][6] += round(($result['count'] / 100 * 9.35), 0);
            $counts['emotions'][7] += round(($result['count'] / 100 * 5.61), 0);
            $counts['emotions'][8] += round(($result['count'] / 100 * 7.94), 0);

            $counts['problems'][1] += round(($result['count'] / 100 * 23.83), 0);
            $counts['problems'][2] += round(($result['count'] / 100 * 22.9), 0);
            $counts['problems'][3] += round(($result['count'] / 100 * 3.74), 0);
            $counts['problems'][4] += round(($result['count'] / 100 * 3.74), 0);
            $counts['problems'][5] += round(($result['count'] / 100 * 1.4), 0);
            $counts['problems'][6] += round(($result['count'] / 100 * 0.93), 0);
            $counts['problems'][7] += round(($result['count'] / 100 * 1.87), 0) + round(($result['count'] / 100 * 2.34), 0);
            $counts['problems'][9] += round(($result['count'] / 100 * 3.74), 0);
            $counts['problems'][10] += round(($result['count'] / 100 * 0.93), 0);
            $counts['problems'][11] += round(($result['count'] / 100 * 1.4), 0);

            if (array_sum($counts['status_passport']) !== $result['count']) {
                $counts['status_passport'][1] += $result['count'] - array_sum($counts['status_passport']);
            }

            if (array_sum($counts['emotions']) !== $result['count']) {
                $counts['emotions'][6] += $result['count'] - array_sum($counts['emotions']);
            }

            if (array_sum($counts['problems']) !== $result['count']) {
                $counts['problems'][1] += $result['count'] - array_sum($counts['problems']);
            }
        }

        $counts['return_job'][0]->count = $counts['return_job'][0]->count + $counts['return_job'][2]->count;

        unset($counts['return_job'][2]);

//            dd($counts);
//            return abort(500);

        return view('pages.statistics.clientsOPT')->with(
            [
                'data' => $result,
                'regions' => $regions,
                'counts' => $counts
            ]
        );

    }
}
