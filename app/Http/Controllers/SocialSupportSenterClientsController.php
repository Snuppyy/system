<?php

namespace App\Http\Controllers;

use App\Region;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Author;

class SocialSupportSenterClientsController extends Controller
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

    public function index() {
        auth()->user()->role !== 1 && auth()->user()->positions->project !== 3 ? abort(403) : '';
        return view('pages.social-support-center.clients.index');
    }

    public function registration() {
        auth()->user()->role !== 1 && auth()->user()->positions->project !== 3 ? abort(403) : '';
        $regions = Region::select('id', 'encoding')->get();
        return view('pages.social-support-center.library.registration.client')->with(['regions' => $regions]);
    }
}
