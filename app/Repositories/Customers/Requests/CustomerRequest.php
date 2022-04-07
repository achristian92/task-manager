<?php

namespace App\Repositories\Customers\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    public function rules() {
        $rules = [
            'name'             => 'required|unique:customers,name',
            'ruc'              => 'nullable|max:11|unique:customers',
            'hours'            => 'nullable|numeric',
            'contact_email'    => 'nullable|email',
            'attachment_image' => ['file','image:png,jpeg,jpg','max:548'],
        ];

        if ( $this->method() === 'PUT' ) {
            $rules =  [
                'name' => ['required','unique:customers,name,'. $this->segment(3)],
                'ruc'  => ['required','unique:customers,ruc,'. $this->segment(3)],
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
