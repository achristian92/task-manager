<?php

namespace App\Exports\Matriz;

use App\Repositories\Customers\Customer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class CustomerExport implements FromView, WithTitle, ShouldAutoSize
{
    private $company_id;

    public function __construct(int $company_id)
    {
        $this->company_id = $company_id;
    }

    public function view(): View
    {
        return view('reports.matriz.customers', [
            'customers' => Customer::whereCompanyId($this->company_id)->orderBy('name')->get()
        ]);
    }

    public function title(): string
    {
        return 'Clientes';
    }
}