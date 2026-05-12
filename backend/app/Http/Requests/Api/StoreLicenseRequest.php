<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreLicenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom'                 => 'required|string|min:2|max:255',
            'quantite_disponible' => 'required|integer|min:1',
            'date_assignation'    => 'required|date',
            'client_id'           => 'sometimes|required|exists:clients,id',
        ];
    }
}
