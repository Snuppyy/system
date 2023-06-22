<?php

namespace App\Exports;

use App\ExportTempExcel;
use App\Questionnaire;
use App\QuestionnaireOPU_001;
use App\Region;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;

class OutreachExport implements FromView, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $filter = [
            session('startDate'),
            session('endDate'),
        ];


        if (session('region') === 'all') {
            $data = DB::table('answers')->where('answers.status', '>=', 1)->where(['answers.project' => 2]);
        } else {
            $data = DB::table('answers')->where('answers.status', '>=', 1)->where(['answers.region' => $region->id, 'answers.project' => 2]);
        }

        $data->whereBetween('date', $filter);
        $data = $data->join('questionnaires', 'questionnaires.id', 'answers.questionnaire')
            ->join('regions', 'regions.id', 'answers.region')
            ->join('outreaches', 'outreaches.id', 'answers.outreach')
            ->select('answers.id', 'questionnaires.encoding as questionnaire_encoding', 'questionnaire', 'regions.encoding as region', 'regions.id as id_region', 'type', 'date', \DB::raw('CONCAT(outreaches.f_name, " ", outreaches.s_name) as outreach'), 'volunteer', \DB::raw('GROUP_CONCAT(CONCAT(type, "/"), answers ORDER BY answers.type ASC SEPARATOR "/") as answers'))
            ->orderBy('answers.type', 'answers.region', 'answers.outreach', 'answers.volunteer')
            ->groupBy('answers.questionnaire', 'answers.region', 'answers.outreach', 'answers.volunteer')
            ->where('answers.status', '>=', 1)
            ->where(['answers.project' => 2])
            ->where('answers.type', '<=', 2);
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

        return view('pages.statistics.outreachesExport')->with(['data' => $data]);
    }

    public function headings(): array
    {
        return [
            "Дата",
            "Аутрич-сотрудник",
            "Волонтер",
            "Регион",
            "Тема опросника",
            "ПРЕ тест",
            "ПОСТ тест"
        ];
    }
}