<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRestaurantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can(
            'update',
            $this->route('restaurant')
        );
    }

    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'string',
                'max:255',
            ],

            'description' => [
                'sometimes',
                'nullable',
                'string',
            ],

            'phone' => [
                'sometimes',
                'nullable',
                'string',
                'max:30',
            ],
        ];
    }
}