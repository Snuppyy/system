<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Assignment;
use App\Client;
use App\Questionnaire;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function test()
    {
        $indicators[0]['name'] = 'Групповое информирование по вопросам туберкулеза';
        $indicators[1]['name'] = 'Индивидуальное информирование по вопросам туберкулеза';
        $indicators[2]['name'] = 'Индивидуальные консультации ТБ по социальным вопросам';
        $indicators[3]['name'] = 'Групповые занятия ТБ по психологическим вопросам';
        $indicators[4]['name'] = 'Индивидуальные консультации ТБ по психологическим вопросам';
        $indicators[5]['name'] = 'Групповая консультация ТБ по юридическим вопросам';
        $indicators[6]['name'] = 'Индивидуальные консультации ТБ по юридическим вопросам';

        $indicators[0]['responsibility'] = 3;
        $indicators[1]['responsibility'] = 3;
        $indicators[2]['responsibility'] = 18;
        $indicators[3]['responsibility'] = 2;
        $indicators[4]['responsibility'] = 1;
        $indicators[5]['responsibility'] = 17;
        $indicators[6]['responsibility'] = 16;

        $indicators[0]['type'] = 'multiple';
        $indicators[1]['type'] = 'once';
        $indicators[2]['type'] = 'once';
        $indicators[3]['type'] = 'multiple';
        $indicators[4]['type'] = 'once';
        $indicators[5]['type'] = 'multiple';
        $indicators[6]['type'] = 'once';

        $activitiesClients = Activity::leftJoin('assignments', 'assignments.id', '=', 'activities.assignment')
            ->where('activities.status', '>', 0)
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
                            $indicators[$key]['all']['actions'][$activitiesClient->date->format('Y-m-d')] = $activitiesClient->date->format('Y-m-d');
                        }
                    }
                }
            }
        }

        foreach ($indicators as $key => $indicator) {
            foreach ($indicators[$key]['all']['clients'] as $client => $date) {
                $prison = Client::find($client);
                $indicators[$key]['alll']['clients'][$prison->f_name . ' ' . $prison->s_name . ' - ' . $client] = $date;
                $indicators[$key][$prison->prison]['clients'][$prison->f_name . ' ' . $prison->s_name . ' - ' . $client] = $date;
                $indicators[$key][$prison->prison]['actions'][$date] = $date;
            }
        }

        return view('test')->with('data', $indicators);
    }

    public function test1()
    {
        $test = DB::table('country')->where('id', '=', '191')->get();

        dd($test);

        return view('test1')->with('data', $indicators);
    }

    public function test2()
    {
        $assignments = Assignment::where('status', 1)
            ->where('mark', '<>', 'Временное поручение (на 2 квартал)')
            ->get();

        foreach ($assignments as $assignment){
            $data[$assignment->id]['mark'] = $assignment->mark;
            $data[$assignment->id]['start'] = $assignment->start;
            $data[$assignment->id]['end'] = $assignment->end;
            foreach ($assignment->administrants as $position => $responsibility){
                $data[$assignment->id]['users'][] = User::where('position', $position)->first()->name_ru;
            }

        }

        return view('test2')->with('data', $data);
    }

    public function test3()
    {
        $test = DB::table('city')->where('region_id', '=', '1316')->get();



        dd($test);
    }
}
