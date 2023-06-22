<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndicatorsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        dd('index');
    }

    public function save(Request $request)
    {
        dd('save');
    }

    public function statistic()
    {
        dd('stats');
    }
}
