<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDispatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicle_id' => ['required', 'integer', 'exists:heavy_vehicles,id'],
            'operator_id' => ['required', 'integer', 'exists:operators,id'],
            'pit_location' => ['required', 'string', 'max:255'],
            'target_tonnage' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'vehicle_id.required' => 'Vehicle is required.',
            'operator_id.required' => 'Operator is required.',
            'pit_location.required' => 'Pit location is required.',
        ];
    }
}
