<?php

namespace App\Repositories\Tags\Repository;

use App\Repositories\Activities\Activity;
use App\Repositories\Histories\UserHistory;
use App\Repositories\Tags\Tag;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Prettus\Repository\Eloquent\BaseRepository;

class TagRepo extends BaseRepository implements ITag
{

    public function model()
    {
        return Tag::class;
    }

    public function listTags(string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*'])
    {
        return $this->model::whereCompanyId(companyID())
            ->whereIsActive(true)
            ->orderBy($orderBy,$sortBy)
            ->get($columns);
    }

    public function listAllTags(string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*'])
    {
        return $this->model::whereCompanyId(companyID())
            ->orderBy($orderBy,$sortBy)
            ->get($columns);
    }

    public function deleteTag(int $tag_id): bool
    {
        $isDelete = true;
        $tag = $this->model::find($tag_id);
        if ($tag->activities()->exists()) {
            $tag->update(['is_active' => false]);
            $isDelete = false;
            history(UserHistory::DISABLE,"Desabilitó la etiqueta $tag->name",$tag);
        } else {
            history(UserHistory::DELETE,"Eliminó la etiqueta $tag->name",$tag);
            $tag->delete();
        }

        return $isDelete;
    }

    public function percentage(Collection $activities)
    {
        return $activities->groupBy('tagName')
            ->map(function ($activities, $tag) {
                list($hour, $minute) = explode(':', sumTime($activities,'timeReal'));
                return [
                    'label' => $tag,
                    'value' => $hour
                ];
            })->values();
    }

    public function historyHours(array $months, $sub = 6)
    {
        $activities = Activity::with(['tag'])
            ->whereCompanyId(companyID())
            ->whereDate('start_date','>=',$months['fromYmd'])
            ->whereDate('due_date','<=',$months['toYmd'])
            ->get()->transform(function ($activity) {
                return [
                    'tagId'    => $activity->tag_id,
                    'tagName'  => $activity->tag->name,
                    'timeReal' => $activity->time_real,
                    'month'    => Carbon::parse($activity->start_date)->format('Y-m')
                ];
            });

        $dataset = $activities->groupBy('tagName')->map(function ($activities, $tag) use ( $months ) {
            foreach ($months['formatYm'] as $month) {
                $bymonth = $activities->where('month',$month);
                list($hour, $minute) = explode(':', sumTime($bymonth,'timeReal'));
                $hours[] = $hour;
            }
            return [
                'seriesname' => Str::limit($tag, 15),
                'data' => self::transformDataSource($hours,'value'),
            ];
        })->values();

        return [
            'categories' => self::transformDataSource($months['names'],'label'),
            'dataset' => $dataset
        ];
    }

    private function transformDataSource(array $data, string $text)
    {
        return collect($data)->map(function ($name) use ($text){
            return [ $text => $name];
        });
    }
}
