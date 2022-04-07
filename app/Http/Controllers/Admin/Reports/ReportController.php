<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function __invoke()
    {
        return view('admin.reports.index');
    }
}
