<?php

namespace App\Http\Controllers;

use App\Client;
use App\Prison;
use App\Region;
use Illuminate\Http\Request;

class RegistrationClientController extends Controller
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
        $prisons = Prison::where('project', 4)->select('id', 'name_' . app()->getLocale() . ' as name')->get();
        $regions = Region::where('status', 1)->select('id', 'encoding')->get();
        return view('pages.library.registration.client')->with(['prisons' => $prisons, 'regions' => $regions]);
    }

    public function set(Request $request)
    {
        if(\Auth::user()->role !== 1) $request->merge(['region' => \Auth::user()->region->id === 0 ? 1 : \Auth::user()->region->id]);
        $request->author = auth()->user()->id;
        Client::create($request->except('_token'));
        $message = 'Клиент '.$request->f_name.' '.$request->s_name.' успешно зарегестрирован!';
        return redirect()->back()->with('success', $message);
    }
}
