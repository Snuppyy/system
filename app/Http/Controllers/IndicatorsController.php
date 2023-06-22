<?php

namespace App\Http\Controllers;

use App\indicator;
use App\indicatorStats;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IndicatorsExport;

class IndicatorsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $indicators = indicator::where('status', '>=', 1)->get();
        $data = indicatorStats::where('author', auth()->user()->id)->first()->indicators;
        return view('pages.indicators.index',
            [
                'indicators' => $indicators,
                'data' => $data
            ]
        );
    }

    public function save(Request $request)
    {
        $data = indicatorStats::updateOrCreate(
            ['author' => auth()->user()->id],
            ['indicators' => $request->indicators]
        );
        return $data;
    }

    public function statistic()
    {
        return Excel::download(new IndicatorsExport, 'indicators.xlsx');
    }
}
