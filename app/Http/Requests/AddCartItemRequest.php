<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AddCartItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'menu_item_id' => [
                'required',
                'exists:menu_items,id',
            ],

            'menu_items_variants_id' => [
                'required',
                'exists:menu_items_variants,id',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'confirm_restaurant_change' => [
            'nullable',
            'boolean',
            ],
        ];
    }
}
