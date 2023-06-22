<?php

namespace App\Exports;

use App\ExportTempExcel;
use App\QuestionnaireOPU_001;
use App\Region;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;

class StatsExport implements FromView, WithHeadings
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
            $data = DB::table('questionnaire_o_p_u_001s')->where('status', '>=', 1)->where(['project' => 2]);
        } else {
            $id_region = Region::whereEncoding(session('region'))->get()->first()->id;
            $data = DB::table('questionnaire_o_p_u_001s')->where('status', '>=', 1)->where(['region' => $id_region, 'project' => 2]);
        }
        $data->whereBetween('date', $filter);

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
            if ($items->OfferFluorography === 1 && $items->EscortFluorography === 1) $return[11][8] += 1;
            if ($items->OfferFluorography === 0 && $items->EscortFluorography === 0) $return[11][9] += 1;
            if ($items->OfferFluorography === 0 && $items->EscortFluorography === 0 && $date_fluorography->diffInMonths($now) <= 12) $return[11][10] += 1;
            if ($items->OfferFluorography === 0 && $items->EscortFluorography === 0 && $date_fluorography->diffInMonths($now) > 12) $return[11][11] += 1;

            $return[12][1] += $items->RegistrationHiv === 1 ? 1 : 0;


            $start = new Carbon($filter[0]);
            $end = new Carbon($filter[1]);
            if ($date_fluorography >= $start && $date_fluorography <= $end) $return[13][2] += 1;
            if ($date_hiv >= $start && $date_hiv <= $end) $return[13][1] += 1;
            if ($date_fluorography >= $start && $date_fluorography <= $end && $items->EscortHiv == 1) $return[13][2] += 1;
            if ($date_hiv >= $start && $date_hiv <= $end && $items->EscortFluorography == 1) $return[13][1] += 1;
        }

        return view('pages.statistics.clientsExport', [
            'data' => $return,
            'count' => $data->count()
        ]);
    }

    public function headings(): array
    {
        return [
            "Регион",
            "Условие",
            "Количество",
            "Процент"
        ];
    }
}