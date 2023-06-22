<?php

namespace App\Http\Controllers;

use App\Answer;
use App\MioVisition;
use App\Questionnaire;
use App\QuestionnaireOPU_001;
use App\Region;
use App\SupportMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Laravel\Facades\Telegram;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

//        $latestId = isset(SupportMessage::latest('update_id')->first()->update_id) ? SupportMessage::latest('update_id')->first()->update_id : 0;
//        $messages = Telegram::getUpdates([
//            'offset' => $latestId ? $latestId+1 : '',
//        ]);
//        foreach ($messages as $message) {
//            if(isset($message['message']['reply_to_message'])) {
//                $startPos = strpos($message['message']['reply_to_message']['text'], '#user');
//                $endPos = strpos($message['message']['reply_to_message']['text'], 'IP:');
//                $hashtag = substr($message['message']['reply_to_message']['text'], $startPos, $endPos - $startPos - 1);
//                $user_id = (int) filter_var($hashtag, FILTER_SANITIZE_NUMBER_INT);
//                $data = [
//                    'chat_id' => $message['message']['chat']['id'],
//                    'message_id' => $message['message']['message_id'],
//                    'update_id' => $message['update_id'],
//                    'author' => auth()->user()->id,
//                    'user' => $user_id,
//                    'reply_to_message_id' => $message['message']['reply_to_message']['message_id'],
//                    'text' => $message['message']['text'],
//                ];
//                SupportMessage::create($data);
//            }
//        }

//        Общая диаграмма

        $Answers = DB::table('answers');
        $OPU = DB::table('questionnaire_o_p_u_001s');
        $MiO = DB::table('mio_visitions');
        $regions = DB::table('regions')->where('id', '<>', 0)->get();
        if (isset($request->region)) {
            \Auth::user()->role <= 3 ? $countAnswers = $Answers->where('status', '>=', 1)->where(['region' => $request->region, 'project' => 2])->count('id') : $countAnswers = $Answers->where(['status' => 1, 'region' => \Auth::user()->region->id, 'project' => 2])->count('id');
            \Auth::user()->role <= 3 ? $countMiO = $MiO->where('status', '>=', 1)->where(['region' => $request->region, 'project' => 2])->count('id') : $countMiO = $MiO->where(['status' => 1, 'region' => \Auth::user()->region->id, 'project' => 2])->count('id');
            \Auth::user()->role <= 3 ? $countOPU = $OPU->where('status', '>=', 1)->where(['region' => $request->region, 'project' => 2])->count('id') : $countOPU = $OPU->where(['status' => 1, 'region' => \Auth::user()->region->id, 'project' => 2])->count('id');
        } else {
            \Auth::user()->role <= 3 ? $countAnswers = $Answers->where('status', '>=', 1)->where(['project' => 2])->count('id') : $countAnswers = $Answers->where(['status' => 1, 'region' => \Auth::user()->region->id, 'project' => 2])->count('id');
            \Auth::user()->role <= 3 ? $countMiO = $MiO->where('status', '>=', 1)->where(['project' => 2])->count('id') : $countMiO = $MiO->where(['status' => 1, 'region' => \Auth::user()->region->id, 'project' => 2])->count('id');
            \Auth::user()->role <= 3 ? $countOPU = $OPU->where('status', '>=', 1)->where(['project' => 2])->count('id') : $countOPU = $OPU->where(['status' => 1, 'region' => \Auth::user()->region->id, 'project' => 2])->count('id');
        }

//        Мониторинговые визиты

        $resultMio = [];
        function rand_color($num)
        {
            $hash = md5('color' . $num); // modify 'color' to get a different palette
            return 'rgba('.hexdec(substr($hash, 0, 2)).', '.hexdec(substr($hash, 2, 2)).', '.hexdec(substr($hash, 4, 2)).', 0.8)';
        }

        if (\Auth::user()->region->id === 0) {
            $miovisitions = MioVisition::leftJoin('regions', 'regions.id', '=', 'mio_visitions.region')->where('mio_visitions.status', '>=', 1)->where(['mio_visitions.project' => 2])->groupBy(['mio_visitions.region', DB::raw('MONTH(`datetime`)'), DB::raw('YEAR(`datetime`)')])->select('mio_visitions.datetime', 'regions.encoding as region', DB::raw('COUNT(`mio_visitions`.`id`) as `count`'))->orderBy('mio_visitions.datetime')->get()->toArray();
        } else {
            $miovisitions = MioVisition::leftJoin('regions', 'regions.id', '=', 'mio_visitions.region')->where('mio_visitions.status', '>=', 1)->where(['mio_visitions.project' => 2, 'mio_visitions.region' => auth()->user()->region->id])->groupBy(['mio_visitions.region', DB::raw('MONTH(`datetime`)'), DB::raw('YEAR(`datetime`)')])->select('mio_visitions.datetime', 'regions.encoding as region', DB::raw('COUNT(`mio_visitions`.`id`) as `count`'))->orderBy('mio_visitions.datetime')->get()->toArray();
        }

        foreach ($miovisitions as $miovisition) {
            $date = Carbon::parse($miovisition['datetime']);
            $month[] = $date->monthName . ' ' . $date->year;
        }
        $month = array_values(array_unique($month));
        $month = array_flip($month);
        $dataResMio = [];
        foreach ($miovisitions as $miovisition) {
            for ($monthCount = 0; $monthCount < count($month); $monthCount++) {
                $dataResMio[$miovisition['region']][$monthCount] = 0;
            }
        }
        foreach ($miovisitions as $miovisition) {
            $date = Carbon::parse($miovisition['datetime']);
            $dataResMio[$miovisition['region']][$month[$date->monthName . ' ' . $date->year]] = $miovisition['count'];
        }
        ksort($dataResMio);
        reset($dataResMio);
        $resultMio['labels'] = array_keys($month);
        $i = 0;
        foreach ($dataResMio as $key => $value) {
            $resultMio['datasets'][$i]['label'] = $key;
            $resultMio['datasets'][$i]['data'] = $value;
            $resultMio['datasets'][$i]['backgroundColor'] = rand_color($key);
            $resultMio['datasets'][$i]['borderWidth'] = 1;
            $resultMio['datasets'][$i]['fill'] = 'true';
            $i++;
        }

//        Оценка услуг

        if (\Auth::user()->region->id === 0) {
            $opus = QuestionnaireOPU_001::leftJoin('regions', 'regions.id', '=', 'questionnaire_o_p_u_001s.region')->where('questionnaire_o_p_u_001s.status', '>=', 1)->where(['questionnaire_o_p_u_001s.project' => 2])->groupBy(['questionnaire_o_p_u_001s.region', DB::raw('MONTH(`date`)'), DB::raw('YEAR(`date`)')])->select('questionnaire_o_p_u_001s.date', 'regions.encoding as region', DB::raw('COUNT(`questionnaire_o_p_u_001s`.`id`) as `count`'))->orderBy('questionnaire_o_p_u_001s.date')->get()->toArray();
        } else {
            $opus = QuestionnaireOPU_001::leftJoin('regions', 'regions.id', '=', 'questionnaire_o_p_u_001s.region')->where('questionnaire_o_p_u_001s.status', '>=', 1)->where(['questionnaire_o_p_u_001s.project' => 2, 'questionnaire_o_p_u_001s.region' => auth()->user()->region->id])->groupBy(['questionnaire_o_p_u_001s.region', DB::raw('MONTH(`date`)'), DB::raw('YEAR(`date`)')])->select('questionnaire_o_p_u_001s.date', 'regions.encoding as region', DB::raw('COUNT(`questionnaire_o_p_u_001s`.`id`) as `count`'))->orderBy('questionnaire_o_p_u_001s.date')->get()->toArray();
        }
        $month = [];
        foreach ($opus as $opu) {
            $date = Carbon::parse($opu['date']);
            $month[] = $date->monthName . ' ' . $date->year;
        }
        $month = array_values(array_unique($month));
        $month = array_flip($month);
        $dataResOPU = [];
        foreach ($opus as $opu) {
            for ($monthCount = 0; $monthCount < count($month); $monthCount++) {
                $dataResOPU[$opu['region']][$monthCount] = 0;
            }
        }
        foreach ($opus as $opu) {
            $date = Carbon::parse($opu['date']);
            $dataResOPU[$opu['region']][$month[$date->monthName . ' ' . $date->year]] = $opu['count'];
        }
        ksort($dataResOPU);
        reset($dataResOPU);
        $resultOPU['labels'] = array_keys($month);
        $i = 0;
        foreach ($dataResOPU as $key => $value) {
            $resultOPU['datasets'][$i]['label'] = $key;
            $resultOPU['datasets'][$i]['data'] = $value;
            $resultOPU['datasets'][$i]['backgroundColor'] = rand_color($key);
            $resultOPU['datasets'][$i]['borderWidth'] = 1;
            $resultOPU['datasets'][$i]['fill'] = 'true';
            $i++;
        }

        if(auth()->user()->region->id === 0) {
            $regions = Region::whereIn('id', [2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14])
                ->select('encoding')
                ->get();
            foreach ($regions as $region) {
                $questRes[$region->encoding]['sum'] = 'N/A';
                $questRes[$region->encoding]['N/A']['N/A'] = 'N/A';
            }
        }

        $questData = Answer::leftJoin('regions', 'regions.id', '=', 'answers.region')
            ->leftJoin('questionnaires', 'questionnaires.id', '=', 'answers.questionnaire');
        auth()->user()->region->id === 0 ? $questData = $questData->where('answers.status', '>=', 1)->where(['answers.project' => 2, 'answers.type' => 2]) : $questData = $questData->where(['answers.status' => 1, 'answers.project' => 2, 'answers.type' => 2, 'answers.region' => auth()->user()->region->id]);
        $request->get('startDate') ? $questData = $questData->whereBetween('date', [$request->get('startDate'), $request->get('endDate')]) : '';
        $questData = $questData->groupBy(DB::raw('MONTH(`answers`.`date`)'), 'answers.region', 'answers.questionnaire')
            ->select('regions.encoding as region', 'answers.date', DB::raw('COUNT(`answers`.`id`) as `count`'), 'questionnaires.encoding as questionnaire')
            ->orderBy('answers.region')
            ->orderBy('answers.questionnaire', 'desc')
            ->orderBy('answers.date', 'desc')
            ->get();

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

        return view('index')->with(['countMiO' => $countMiO, 'countOPU' => $countOPU, 'countAnswers' => $countAnswers, 'regions' => $regions, 'dataMio' => $resultMio, 'dataOPU' => $resultOPU, 'questRes' => $questRes]);
    }
}
