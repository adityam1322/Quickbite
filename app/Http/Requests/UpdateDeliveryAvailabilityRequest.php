<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDeliveryAvailabilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('delivery_partner') ?? false;
    }

    public function rules(): array
    {
        return [
            'is_available' => ['required', 'boolean'],
        ];
    }
}
