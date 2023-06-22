<?php

namespace App\Http\Controllers;

use App\Organization;
use App\User;
use Illuminate\Http\Request;

class OrganizationController extends Controller
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
        $users = User::where('status', 1)->select('id', 'name_' . app()->getLocale() . ' as name')->get();
        $organizations = Organization::where('status', 1)->select('id', 'encoding', 'name_'.app()->getLocale().' as name', 'author', 'alternate', 'address_'.app()->getLocale().' as address')->get();
        return view('pages.settings.projects.organizations')->with(['organizations' => $organizations, 'users' => $users]);
    }

    public function add(Request $request)
    {
        Organization::create(array_merge($request->except('_token'), ['author' => \Auth::user()->id]));
        return redirect()->back();
    }
}
