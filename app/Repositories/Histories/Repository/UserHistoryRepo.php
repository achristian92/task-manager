<?php

namespace App\Repositories\Histories\Repository;

use App\Repositories\Histories\UserHistory;
use Prettus\Repository\Eloquent\BaseRepository;

class UserHistoryRepo extends BaseRepository implements IUserHistory
{

    public function model()
    {
        return UserHistory::class;
    }

    public function listHistory(string $from, string $to)
    {
        return $this->model::whereCompanyId(companyID())
            ->whereDate('created_at','>=',$from)
            ->whereDate('created_at','<=',$to)
            ->orderBy('created_at','desc')
            ->get();
    }

    public function listHistoryByUser($id, string $from, string $to)
    {
        return $this->model::whereUserId($id)
            ->whereDate('created_at','>=',$from)
            ->whereDate('created_at','<=',$to)
            ->orderBy('created_at','desc')
            ->get();
    }


}
