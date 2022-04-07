<?php

namespace App\Http\Controllers\Admin\History;

use App\Http\Controllers\Controller;
use App\Repositories\History\UserHistory;

class   UserController extends Controller
{
    public function __invoke()
    {
        $histories = UserHistory::whereCompanyId(companyID())
            ->orderBy('created_at','desc')
            ->paginate(100);

        return view('admin.history.users',[
            'histories' => $histories
        ]);
    }
}
