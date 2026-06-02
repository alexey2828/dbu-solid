<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TtnUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'idOrder' => 'required|integer',
            'idPlant' => 'sometimes|integer|nullable',
            'dispatcher' => 'sometimes|string|nullable|max:255',
            'driver' => 'sometimes|string|nullable|max:255',
            'car' => 'sometimes|string|nullable|max:255',
            'finishAdress' => 'sometimes|string|nullable|max:255',
            'finishDate' => 'sometimes|date|nullable',
            'bsu' => 'sometimes|integer|required_without:idBsu',
            'idBsu' => 'sometimes|integer|required_without:bsu',
            'json' => 'required',
        ];
    }
}
