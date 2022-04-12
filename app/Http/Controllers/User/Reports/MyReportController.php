<?php

namespace App\Http\Controllers\User\Reports;

use App\Http\Controllers\Controller;

class MyReportController extends Controller
{
    public function __invoke()
    {
        return view('user.reports.index', [
            'users' => [$this->currentUser()],
        ]);
    }
}
