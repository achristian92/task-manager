<?php

namespace App\Http\Controllers\Admin\History;

use App\Http\Controllers\Controller;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Customers\Repository\ICustomer;
use App\Repositories\Documents\Repository\IUserDocument;
use App\Repositories\Histories\Repository\IUserHistory;
use App\Repositories\Tags\Repository\ITag;
use App\Repositories\Tools\DatesTrait;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    use DatesTrait;

    private IUserHistory $userHistoryRepo;
    private IUserDocument $userDocumentRepo;

    public function __construct(IUserHistory $IUserHistory, IUserDocument $IUserDocument)
    {
        $this->userHistoryRepo = $IUserHistory;
        $this->userDocumentRepo = $IUserDocument;
    }

    public function history(Request $request)
    {
        $date = $this->getDateFormats($request->input('yearAndMonth'));

        return view('admin.history.user',[
            'history' => $this->userHistoryRepo->listHistory($date['from'],$date['to'])
        ]);
    }

    public function document(Request $request)
    {
        $date = $this->getDateFormats($request->input('yearAndMonth'));

        return view('admin.history.document',[
            'documents' => $this->userDocumentRepo->listDocuments($date['from'],$date['to'])
        ]);
    }
}
