<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreLicenseOfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom'                 => 'required|string|min:2|max:255',
            'description'         => 'nullable|string|max:1000',
            'prix'                => 'required|numeric|min:0',
            'quantite_disponible' => 'required|integer|min:0',
        ];
    }
}
