<?php

namespace App\Repositories\Companies\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{
    public function rules() {
        $rules = [
            'name'             => 'required|unique:companies,name',
            'ruc'              => 'required|unique:companies,ruc',
            'hours'            => 'nullable|numeric',
            'attachment_image' => ['file','image:png,jpeg,jpg','max:548'],
        ];

        if ( $this->method() === 'PUT' ) {
            $rules =  [
                'name' => 'required|unique:companies,name,'. $this->segment(3),
                'ruc'  => 'required|unique:companies,ruc,'. $this->segment(3),
            ];
        }

        return $rules;

    }
    public function messages()
    {
        return [
            'name.required' => "El nombre es obligatorio.",
            'name.unique'   => "El nombre ya existe.",
            'ruc.required'  => "El Ruc es obligatorio.",
            'ruc.unique'    => "El Ruc ya existe.",
            'hours.numeric' => 'Horas mensuales debe ser númerico.'
        ];
    }
}
