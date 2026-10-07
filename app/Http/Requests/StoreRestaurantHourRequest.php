<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRestaurantHourRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can(
            'create',
            RestaurantHour::class
        );
    }

    public function rules(): array
    {
        return [
            'restaurant_id' => [
                'required',
                'exists:restaurants,id',
            ],

            'day_of_week' => [
                'required',
                'integer',
                'between:0,6',
            ],

            'open_time' => [
                'required',
                'date_format:H:i',
            ],

            'close_time' => [
                'required',
                'date_format:H:i',
            ],

            'is_closed' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}