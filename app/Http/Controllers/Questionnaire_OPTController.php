<?php

namespace App\Http\Controllers;

use App\Prison;
use App\QuestionnaireOPU_001;
use App\Region;
use App\TuberculosisOPT;
use Illuminate\Http\Request;
use Webmozart\Assert\Assert;

class Questionnaire_OPTController extends Controller
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

    public function index()
    {
        $prisons = Prison::select('name_' . app()->getLocale() . ' as name', 'id')->get();
        $regions = Region::select('encoding', 'id')->whereIn('id', [1,3,11])->get();
        return view('pages.tuberculosis.questionnaire-OPT_index')->with(['prisons' => $prisons, 'regions' => $regions]);
    }

    public function save(Request $request)
    {
        if(!$request->region) $request->merge(['region' => \Auth::user()->region->id === 0 ? 1 : \Auth::user()->region->id])->post();
        $data = array_merge($request->except('_token'), ['author' => \Auth::user()->id]);
        try
        {
            $result = TuberculosisOPT::create($data);
        }
        catch(Exception $e)
        {
            dd($e->getMessage());
        }
        return redirect()->back();

    }
}
