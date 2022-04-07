<?php

namespace App\Repositories\Tags\Repository;

use Illuminate\Database\Eloquent\Collection;

interface ITag
{
    public function listTags(string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*']);

    public function listAllTags(string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*']);

    public function deleteTag(int $tag_id): bool;

    public function percentage(Collection $activities);

    public function historyHours(array $months, $sub = 6);
}
