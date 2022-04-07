<?php

namespace App\Repositories\Tags\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => "El nombre de la etiqueta es obligatorio.",
        ];
    }

}
