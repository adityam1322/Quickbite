<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRestaurantHourRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can(
        'update',
        $this->route('restaurant_hour')
    );
    }

    public function rules(): array
    {
        return [
            'restaurant_id' => [
                'sometimes',
                'exists:restaurants,id',
            ],

            'day_of_week' => [
                'sometimes',
                'integer',
                'between:0,6',
            ],

            'open_time' => [
                'sometimes',
                'date_format:H:i',
            ],

            'close_time' => [
                'sometimes',
                'date_format:H:i',
            ],

            'is_closed' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}