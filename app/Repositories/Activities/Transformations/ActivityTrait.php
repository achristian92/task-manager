<?php

namespace App\Repositories\Activities\Transformations;

use App\Repositories\Activities\Activity;
use Carbon\Carbon;
use Illuminate\Support\Collection;

trait ActivityTrait
{
    public function qtyCompleted(Collection $activities):int
    {
        return $activities->where('status',Activity::TYPE_COMPLETED)->count();
    }
    private function qtyPending(Collection $activities): int
    {
        return $activities->where('status','!=',Activity::TYPE_COMPLETED)
            ->where('status','!=',Activity::TYPE_OVERDUE)
            ->count();
    }
    public function qtyPlanned(Collection $activities):int
    {
        return $activities->where('status',Activity::TYPE_PLANNED)->count();
    }
    public function qtyPartial(Collection $activities):int
    {
        return $activities->where('status',Activity::TYPE_PARTIAL)->count();
    }
    public function qtyApproved(Collection $activities):int
    {
        return $activities->where('status',Activity::TYPE_APPROVED)->count();
    }
    public function qtyOverdue(Collection $activities):int
    {
        return $activities->where('status',Activity::TYPE_OVERDUE)->count();
    }


    public function applyFilterByTypeTab(Collection $activities,$typeTab = 'today')
    {
        if ($typeTab === 'today') {
            return $this->filterByDate(Carbon::now(),$activities);
        } else if ($typeTab === 'proximate') {
            return $this->filterAfterDate(Carbon::now(),$activities);
        } else if ($typeTab === 'overdue') {
            return $this->filterOverdue(Carbon::now(),$activities);
        } else if ($typeTab === 'evaluation') {
            return $this->filterEvaluation($activities);
        }

        return $activities;
    }

    public function filterByDate(Carbon $date, Collection $activities): array
    {
        return $activities->filter(function ($activity) use ($date) {
            return $activity['startDate'] === $date->format('Y-m-d') ||
                ($date->format('Y-m-d') >= $activity['startDate'] && $date->format('Y-m-d') <= $activity['dueDate']);
        })->values()->all();
    }
    public function filterAfterDate(Carbon $date, Collection $activities): array
    {
        return  $activities->where('startDate','>', $date->format('Y-m-d'))->sortBy('startDate')->values()->all();
    }
    public function filterOverdue(Carbon $date, Collection $activities): array
    {
        return $activities->filter(function ($activity) use ($date) {
            return $activity['status'] != Activity::TYPE_COMPLETED && $activity['dueDate'] < $date->format('Y-m-d');
        })->sortByDesc('startDate')->values()->all();
    }
    public function filterEvaluation(Collection $activities): array
    {
        return $activities->filter(function ($activity) {
            return $activity['diff_completed_date'] && !$activity['changeDateBy'] && $activity['status'] !== "partial" ;
        })->sortByDesc('startDate')->values()->all();
    }
}
