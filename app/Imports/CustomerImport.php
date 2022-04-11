<?php

namespace App\Imports;

use App\Repositories\Activities\Activity;
use App\Repositories\Customers\Customer;
use App\Repositories\Tags\Tag;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomerImport implements ToCollection,WithValidation,WithHeadingRow
{
    private int $company_id;

    public function __construct(int $company_id)
    {
        $this->company_id = $company_id;
    }

    public function collection(Collection $rows)
    {
        $rows->each(function ($row) {
            Customer::updateOrCreate(
                [
                    'ruc'        => $row['ruc'],
                    'company_id' => $this->company_id
                ],
                [
                    'name'       => $row['empresa'],
                    'address'    => $row['direccion'],
            ]);
        });
    }

    public function rules(): array
    {
        return [
            '*.empresa'   => [
                'required',
                'max:255',
                Rule::unique('customers','name')->where(function ($query) {
                    return $query->where('company_id',  '=', $this->company_id);
                })
            ],
            '*.ruc'   => [
                'required',
                'max:11',
                Rule::unique('customers','ruc')->where(function ($query) {
                    return $query->where('company_id',  '=', $this->company_id);
                })
            ]
        ];
    }
}
