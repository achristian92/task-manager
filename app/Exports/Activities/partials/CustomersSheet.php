<?php

namespace App\Exports\Activities\partials;

use App\Repositories\Customers\Customer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class CustomersSheet  implements FromView, WithTitle, ShouldAutoSize
{
    private $customers;

    public function __construct(array $customers)
    {
        $this->customers = $customers;
    }

    public function view(): View
    {
        return view('reports.matriz.customers', [
            'customers' => $this->customers
        ]);
    }

    public function title(): string
    {
        return 'Clientes';
    }
}
