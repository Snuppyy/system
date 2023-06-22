<?php

namespace App\Exports;

use App\ExportTempExcel;
use App\QuestionnaireOPU_001;
use App\Region;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CommentsExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $id_region = Region::whereEncoding(session('region'))->get()->first()->id;

        return QuestionnaireOPU_001::leftJoin('regions', 'regions.id', '=', 'questionnaire_o_p_u_001s.region')
            ->where('questionnaire_o_p_u_001s.region', $id_region)
            ->whereBetween('date', [session('startDate'), session('endDate')])
            ->select('regions.encoding as region', 'questionnaire_o_p_u_001s.encoding as client', 'Syringes2NotLike', 'Syringes5NotLike', 'Syringes10NotLike', 'DoilyNotLike', 'CondomsMNotLike', 'CondomsWNotLike', 'LimitationsHiv', 'LimitationsFluorography', 'TalkOutreach', 'services')
            ->get();
    }

    public function headings(): array
    {
        return [
            "Регион",
            "Клиент",
            "Что не нравится в 2мг шприцах",
            "Что не нравится в 5мг шприцах",
            "Что не нравится в 10мг шприцах",
            "Что не нравится в салфетках",
            "Что не нравится в мужских презервативах",
            "Что не нравится в женских презервативах",
            "Недостатки процедуры теста ВИЧ",
            "Недостатки процедуры теста ТБ",
            "О чем разговаривают с аутричем",
            "Какие услуги хотят получать"
        ];
    }
}