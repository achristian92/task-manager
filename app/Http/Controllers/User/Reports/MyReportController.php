<?php

namespace App\Http\Controllers\User\Reports;

use App\Http\Controllers\Controller;

class MyReportController extends Controller
{
    public function __invoke()
    {
        $user = \Auth::user();
        $users = [
            [
                'id' => $user->id,
                'text' => $user->full_name
            ]
        ];
        return view('user.reports.index', [
            'users' => $users,
        ]);
    }
}
