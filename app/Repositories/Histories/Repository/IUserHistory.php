<?php

namespace App\Repositories\Histories\Repository;

interface IUserHistory
{
    public function listHistory(string $from, string $to);

    public function listHistoryByUser($id, string $from, string $to);

}
