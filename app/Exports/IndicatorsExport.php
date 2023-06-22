<?php

namespace App\Exports;

use App\indicator;
use App\indicatorStats;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IndicatorsExport implements FromView
{
    public function view(): View
    {
        $data = indicatorStats::all();
        foreach ($data as $datum)
        {
            foreach ($datum->indicators as $index => $id)
            {
                $indicatorsData[$id] += $index;
            }
        }

        ksort($indicatorsData);
        $indicators = indicator::where('status', '>=', 1)->get();
        $count = $data->count();

        foreach ($indicatorsData as $id => $rating) {
            $indicatorsData[$id] = round($rating/$count,3);
        }

        asort($indicatorsData);

        return view('pages.indicators.stats')->with(
            [
                'count' => $count,
                'indicators' => $indicators,
                'data' => $indicatorsData
            ]
        );
    }
}
