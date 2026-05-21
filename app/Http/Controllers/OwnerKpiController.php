<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OwnerKpiController extends Controller
{
    public function index()
    {
        return view('owner.kpi');
    }
}
