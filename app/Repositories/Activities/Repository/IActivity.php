<?php

namespace App\Repositories\Activities\Repository;

use App\Repositories\Activities\Activity;
use Illuminate\Support\Collection;

interface IActivity
{
    public function createActivity(array $params): Activity;

    public function updateActivity(array $params, int $id): bool ;

    public function deleteActivity(int $id): bool ;


    public function approve(int $id);

    public function backToApprove(int $id);

    public function backToPlanned(int $id);


    public function listActivitiesToDashboard(string $from, string $to);

    public function progress(Collection $activities): float;

    public function resume(Collection $activities): array;

    public function resumeByUser(Collection $activities): array;

    public function workPlansByUser(int $user_id, $from, $to);

    public function workPlanOnlyStatusPlannedsByUser(int $user_id, $from, $to);

    public function listActivityByUser(int $id, $from, $to);

    public function listActivityByCustomer(int $id, $from, $to);

    public function listActivities(string $from, string $to);

    public function queryActivitiesReport(string $from, string $to);
}
