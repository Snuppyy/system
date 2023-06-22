<?php

namespace App\Http\Controllers;

use App\Outreach;
use App\OutsideOrganization;
use App\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistrationOutreachController extends Controller
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
        $regions = DB::table('regions')->where('status', 1)->select('id', 'encoding')->get();
        $organizations = OutsideOrganization::where('status', 1)->select('id', 'name')->get();
        return view('pages.library.registration.outreach', ['regions' => $regions, 'organizations' => $organizations]);
    }

    public function set(Request $request)
    {
        if(\Auth::user()->role !== 1) $request->merge(['region' => \Auth::user()->region->id === 0 ? 1 : \Auth::user()->region->id]);
        $request->assistant ? $request->merge(['assistant' => 1]) : '';
        $request->online ? $request->merge(['online' => 1]) : '';
        Outreach::create($request->except('_token'));
        $who = !is_null($request->assistant) ? 'Ассистент' : 'Аутрич-сотрудник';
        $message = $who.' - '.$request->f_name.' '.$request->s_name.' успешно зарегестрирован!';
        return redirect()->back()->with('success', $message);
    }

    public function view(Request $request)
    {
        $outreaches = Outreach::leftJoin('regions', 'regions.id', '=', 'outreaches.region')
            ->where('outreaches.status', '>', 0);
        if (\Auth::user()->role !== 1) $outreaches->where('outreaches.region', \Auth::user()->region->id);
        $outreaches->select('regions.encoding as region', 'outreaches.id', 'outreaches.f_name', 'outreaches.s_name', 'outreaches.status', 'outreaches.assistant')
            ->orderBy('outreaches.region')
            ->orderBy('outreaches.f_name');
        return view('pages.library.registration.outreachView')->with(['outreaches' => $outreaches->get()]);
    }

    public function dismiss(Request $request)
    {
        Outreach::where('id', $request->id)->update(['status' => 2]);
        $outreach = Outreach::where('id', $request->id)->first();
        $who = !is_null($outreach->assistant) ? 'Ассистент' : 'Аутрич-сотрудник';
        $message = $who.' - '.$outreach->f_name.' '.$outreach->s_name.' уволен!';
        return redirect()->back()->with('success', $message);
    }

    public function recruit(Request $request)
    {
        Outreach::where('id', $request->id)->update(['status' => 1]);
        $outreach = Outreach::where('id', $request->id)->first();
        $who = !is_null($outreach->assistant) ? 'Ассистент' : 'Аутрич-сотрудник';
        $message = $who.' - '.$outreach->f_name.' '.$outreach->s_name.' принят!';
        return redirect()->back()->with('success', $message);
    }

}
