<?php

namespace App\Http\Requests;

use App\Models\MenuItem;
use Illuminate\Foundation\Http\FormRequest;

class StoreMenuItemRequest extends FormRequest
{
    public function authorize(): bool
{
    return $this->user()->can('create', MenuItem::class);
}

    public function rules(): array
    {
        return [
            'menu_categories_id' => [
                'required',
                'exists:menu_categories,id',
            ],

            'restaurant_id' => [
                'required',
                'exists:restaurants,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'required',
                'string',
                'max:255',
            ],

            'discription' => [
                'required',
                'string',
            ],

            'is_vegetarian' => [
                'required',
                'boolean',
            ],

            'is_available' => [
                'required',
                'boolean',
            ],
        ];
    }
}