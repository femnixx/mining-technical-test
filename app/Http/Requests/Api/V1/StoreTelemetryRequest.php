<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTelemetryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicle_code' => ['required', 'string', 'exists:heavy_vehicles,vehicle_code'],
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'long' => ['required', 'numeric', 'between:-180,180'],
            'speed' => ['nullable', 'numeric', 'min:0'],
            'fuel' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'temp' => ['nullable', 'numeric'],
            'tonnage' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'vehicle_code.required' => 'Vehicle code is required.',
            'vehicle_code.exists' => 'Vehicle code does not exist.',
            'lat.required' => 'Latitude is required.',
            'long.required' => 'Longitude is required.',
        ];
    }
}
