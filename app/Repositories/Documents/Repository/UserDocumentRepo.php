<?php

namespace App\Repositories\Documents\Repository;

use App\Repositories\Documents\UserDocument;
use Prettus\Repository\Eloquent\BaseRepository;

class UserDocumentRepo extends BaseRepository implements IUserDocument
{

    public function model()
    {
        return UserDocument::class;
    }

    public function listDocuments(string $from, string $to)
    {
        return $this->model::whereCompanyId(companyID())
            ->whereDate('created_at','>=',$from)
            ->whereDate('created_at','<=',$to)
            ->orderBy('created_at','desc')
            ->get();
    }

    public function listDocumentsByUser($id)
    {
        return $this->model::whereUserId($id)
            ->orderBy('created_at','desc')
            ->get();
    }


}
