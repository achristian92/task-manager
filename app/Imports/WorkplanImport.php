<?php

namespace App\Imports;

use App\Repositories\Activities\Activity;
use App\Repositories\Customers\Customer;
use App\Repositories\Tags\Tag;
use App\Repositories\Users\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithValidation;

class WorkplanImport implements ToCollection,WithValidation,WithHeadingRow
{


    private User $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function collection(Collection $rows)
    {
        $rows->each(function ($row) {
            $customer_id = $this->getIdCustomerByName($row['cliente']);
            $time = $this->transformDate($row['horas'],'H:i');
            $customer = Customer::find($customer_id);

            if($customer->limitActivities()) {
                if($customer->isValidHourLimit($time)) {
                    $activity = Activity::create(
                        [
                            'start_date'    => $this->transformDate($row['fecha']),
                            'customer_id'   => $this->getIdCustomerByName($row['cliente']),
                            'user_id'       => $this->user->id,
                            'name'          => $row['actividad'],
                            'time_estimate' =>  $time,
                            'tag_id'        => $this->getIdTagByName($row['etiqueta']),
                            'description'   => $row['descripcion'],
                            'due_date'      => $this->transformDate($row['fecha']),
                            'is_priority'   => $row['prioridad'] === 'x',
                            'status'        => Activity::TYPE_PLANNED,
                            'created_by_id' => $this->user->id,
                            'created_date'  => Carbon::now()
                        ]
                    );

                    $activity->histories()->create([
                        'user'          => $this->user->full_name,
                        'description'   => 'Actividad creada',
                        'registered_at' => Carbon::now()
                    ]);
                }
            } else {
                $activity = Activity::create(
                    [
                        'start_date'    => $this->transformDate($row['fecha']),
                        'customer_id'   => $this->getIdCustomerByName($row['cliente']),
                        'user_id'       => $this->user->id,
                        'name'          => $row['actividad'],
                        'time_estimate' =>  $time,
                        'tag_id'        => $this->getIdTagByName($row['etiqueta']),
                        'description'   => $row['descripcion'],
                        'due_date'      => $this->transformDate($row['fecha']),
                        'is_priority'   => $row['prioridad'] === 'x',
                        'status'        => Activity::TYPE_PLANNED,
                        'created_by_id' => $this->user->id,
                        'created_date'  => Carbon::now()
                    ]
                );

                $activity->histories()->create([
                    'user'          => $this->user->full_name,
                    'description'   => 'Actividad creada',
                    'registered_at' => Carbon::now()
                ]);
            }


        });

    }

    public function rules(): array
    {
        return [
            '*.fecha'     => 'required|numeric',
            '*.cliente'   => ['required',Rule::in(Customer::whereCompanyId($this->user->company_id)->pluck('name')->toArray())],
            '*.actividad' => 'required|max:255',
            '*.horas'     => 'required|numeric',
            '*.etiqueta'  => ['required',Rule::in(Tag::whereCompanyId($this->user->company_id)->pluck('name')->toArray())],
        ];
    }
    public function headingRow(): int
    {
        return 7;
    }

    private function getIdCustomerByName($name): int
    {
        $customer = Customer::whereCompanyId($this->user->company_id)->whereName($name)->first();
        if ( !$customer )
            return 0;

        return $customer->id;
    }

    private function getIdTagByName($name): int
    {
        $tag = Tag::whereCompanyId($this->user->company_id)->whereName($name)->first();
        if ( !$tag )
            return 0;

        return $tag->id;
    }

    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->format($format);
        } catch (\ErrorException $e) {
            return Carbon::now($format)->format($value);
        }
    }


}
