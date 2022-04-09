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
use Maatwebsite\Excel\Concerns\WithValidation;

class WorkplanImport implements ToCollection,WithValidation,WithHeadingRow
{

    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function collection(Collection $rows)
    {
        $rows->each(function ($row) {
                $activity = Activity::create(
                    [
                        'start_date'    => $this->transformDate($row['fecha']),
                        'customer_id'   => $row['id_cliente'],
                        'user_id'       => $this->user->id,
                        'name'          => ucfirst(strtolower($row['actividad'])),
                        'time_estimate' =>  $this->transformDate($row['horas'],'H:i'),
                        'tag_id'        => $row['id_etiqueta'],
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
        });

    }

    public function rules(): array
    {
        return [
            '*.fecha'       => 'required|numeric',
            '*.id_cliente'  => ['required',Rule::in(Customer::whereCompanyId($this->user->company_id)->get()->modelKeys())],
            '*.actividad'   => 'required|max:255',
            '*.horas'       => 'required|numeric',
            '*.id_etiqueta' => ['required',Rule::in(Tag::whereCompanyId($this->user->company_id)->get()->modelKeys())],
        ];
    }
    public function headingRow(): int
    {
        return 7;
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
