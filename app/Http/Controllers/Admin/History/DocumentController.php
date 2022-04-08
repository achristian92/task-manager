<?php

namespace App\Http\Controllers\Admin\History;

use App\Http\Controllers\Controller;
use App\Repositories\Documents\UserDocument;

class DocumentController extends Controller
{
    public function __invoke()
    {
        $documents = UserDocument::whereCompanyId(companyID())
            ->orderBy('created_at','desc')
            ->paginate(100);

        return view('admin.history.documents',[
            'documents' => $documents
        ]);
    }
}
