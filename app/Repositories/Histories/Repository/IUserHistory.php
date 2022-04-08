<?php

namespace App\Repositories\Histories\Repository;

interface IUserHistory
{
    public function listHistoryByUser($id, string $from, string $to);

}
