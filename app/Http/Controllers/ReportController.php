<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GlobalVariableController;

class ReportController extends GlobalVariableController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return view('pages.admin.reports.index');
    }
}
