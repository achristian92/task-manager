<?php

namespace App\Exports\Activities;

use App\Exports\Activities\partials\CustomersSheet;
use App\Exports\Activities\partials\TagsSheet;
use App\Exports\Activities\partials\ActivitiesSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TemplatePlannedExport implements WithMultipleSheets
{
    use Exportable;

    private $customers, $tags;

    public function __construct(array $customers, array $tags)
    {
        $this->customers = $customers;
        $this->tags = $tags;
    }

    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new ActivitiesSheet($this->customers, $this->tags);
        $sheets[] = new CustomersSheet($this->customers);
        $sheets[] = new TagsSheet($this->tags);


        return $sheets;
    }
}
