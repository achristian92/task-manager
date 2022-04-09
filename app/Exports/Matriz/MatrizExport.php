<?php

namespace App\Exports\Matriz;


use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MatrizExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct(int $company_id)
    {
        $this->company_id = $company_id;
    }

    public function sheets(): array
    {
        $sheets = [];
        // Agregas las hojas
        $sheets[] = new CustomerExport($this->company_id);
        $sheets[] = new TagExport($this->company_id);

        return $sheets;
    }
}
