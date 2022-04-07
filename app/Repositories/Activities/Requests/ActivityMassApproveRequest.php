<?php

namespace App\Repositories\Activities\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivityMassApproveRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id'      => 'required|exists:users,id',
            'yearAndMonth' => 'required|date',
        ];
    }

}
