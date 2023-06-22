<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\Exports\CommentsExport;
use App\Exports\OutreachExport;
use App\Exports\StatsExport;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class DownloadController extends Controller
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

    public function assignmentsDownload(Request $request)
    {
        $files = Assignment::find($request->id);
        return Storage::download($files->files[$request->key]);
    }

    public function CommentsClient(Request $request){

        \session(['startDate' => $request->startDate]);
        \session(['endDate' => $request->endDate]);
        \session(['region' => $request->region]);

        return Excel::download(new CommentsExport,  'Comments_'.\session('region').'_'.\session('startDate').'_'.\session('endDate').'.xlsx');

    }

    public function StatsClient(Request $request){

        \session(['startDate' => $request->startDate]);
        \session(['endDate' => $request->endDate]);
        \session(['region' => $request->region]);

        return Excel::download(new StatsExport,  'Stats_'.\session('region').'_'.\session('startDate').'_'.\session('endDate').'.xlsx');

    }

    public function StatsOutreach(Request $request){

        \session(['startDate' => $request->startDate]);
        \session(['endDate' => $request->endDate]);
        \session(['region' => $request->region]);

        return Excel::download(new OutreachExport,  'Outreach_'.\session('region').'_'.\session('startDate').'_'.\session('endDate').'.xlsx');

    }
}
