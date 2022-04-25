<?php

namespace App\Repositories\Customers\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
{
    public function rules() {
        $rules = [
            'name'             => [
                'required',
                 Rule::unique('customers','name')->where(function ($query) {
                        return $query->where('company_id',  '=', companyID());
                })
            ],
            'ruc'              => [
                'nullable',
                'max:11',
                Rule::unique('customers','ruc')->where(function ($query) {
                    return $query->where('company_id',  '=', companyID());
                })
            ],
            'hours'            => 'nullable|numeric',
            'contact_email'    => 'nullable|email',
            'attachment_image' => ['file','image:png,jpeg,jpg','max:548'],
        ];

        if ( $this->method() === 'PUT' ) {
            $rules =  [
                'name' => ['required',
                    'required',
                    Rule::unique('customers','name')->where(function ($query) {
                        return $query->where('company_id',  '=', companyID());
                    })->ignore($this->segment(3))
                ],
                'ruc'  => [
                    'required',
                    Rule::unique('customers','ruc')->where(function ($query) {
                        return $query->where('company_id',  '=', companyID());
                    })->ignore($this->segment(3))
                ],
            ];
        }

        return $rules;
    }
    public function messages()
    {
        return [
            'name.required'       => "El nombre del cliente es obligatorio.",
            'name.unique'         => "El nombre ya existe.",
            'contact_email.email' => "El correo de contacto es incorrecto.",
            'hours.numeric'       => 'Horas mensuales debe ser númerico.'
        ];
    }

}
