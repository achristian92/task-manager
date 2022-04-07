<?php

namespace App\Repositories\Users\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'name'          => 'required',
            'last_name'     => 'required',
            'nro_document'  => 'required|unique:users,nro_document',
            'email'         => 'required|email|unique:users,email',
            'attachment_image' => ['file','image:png,jpeg,jpg','max:548'],

        ];

        if ( $this->method() === 'PUT' ) {
            $rules =  [
                'nro_document' => 'required|unique:users,nro_document,'. $this->segment(3),
                'email'  => 'required|email|unique:users,email,'. $this->segment(3),
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required'      => "El nombre es obligatorio.",
            'last_name.required' => "El apellido es obligatorio.",
            'email.required'     => "El correo electronico es obligatorio.",
            'email.email'        => "El correo electronico es inválido.",
            'email.unique'       => "El correo electronico ya esta en uso.",
        ];
    }

}
