<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuditRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'building.name' => ['required', 'string', 'max:255'],
            'building.address' => ['nullable', 'string', 'max:255'],
            'building.building_type' => ['nullable', 'string', 'max:100'],
            'building.square_footage' => ['required', 'numeric', 'min:1'],
            'building.floors' => ['nullable', 'integer', 'min:1'],
            'building.occupants' => ['nullable', 'integer', 'min:0'],

            'appliances' => ['required', 'array', 'min:1'],
            'appliances.*.name' => ['required', 'string', 'max:255'],
            'appliances.*.category' => ['nullable', 'string', 'max:100'],
            'appliances.*.wattage' => ['required', 'numeric', 'min:0'],
            'appliances.*.quantity' => ['required', 'integer', 'min:0'],
            'appliances.*.hours' => ['required', 'numeric', 'min:0', 'max:24'],
            'appliances.*.checked' => ['nullable', 'boolean'],

            'rate_per_kwh' => ['nullable', 'numeric', 'min:0'],
            'email' => ['nullable', 'email'],
        ];
    }
}
