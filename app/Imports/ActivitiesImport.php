<?php

namespace App\Imports;

use App\Repositories\Users\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ActivitiesImport implements WithMultipleSheets, SkipsUnknownSheets
{

    private User $user;

    public function __construct($user)
    {
        $this->user = $user;
    }


    public function sheets(): array
    {
        return [
            'Actividades' => new WorkplanImport($this->user),
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        info("Hoja {$sheetName} fue omitido");
    }
}
