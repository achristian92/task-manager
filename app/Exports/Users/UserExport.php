<?php

namespace App\Exports\Users;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class UserExport  implements FromView,ShouldAutoSize,WithCustomStartCell,WithEvents
{
    private Collection $users;

    public function __construct(Collection $users)
    {
        $this->users = $users;
    }

    public function view(): View
    {
        return view('reports.users.index',[
            'users' => $this->users
        ]);
    }

    public function startCell(): string
    {
        return 'B2';
    }

    public function registerEvents(): array
    {
        $style    = EventExportStyles();
        return [
            AfterSheet::class => function(AfterSheet $event) use ($style) {
                $event->sheet->setShowGridlines(false);
                /* Styles */
                $event->sheet->getDelegate()
                    ->getStyle('A1:H1')
                    ->applyFromArray($style['HEADER']);

                /* Row Height */
                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(25);
            }
        ];
    }
}

