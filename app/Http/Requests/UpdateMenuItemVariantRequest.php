<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuItemVariantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can(
          'update',
         $this->route('menuItem_Variant')
        );
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
                'sometimes',
                'exists:menu_items,id',
            ],

            'name' => [
                'sometimes',
                'string',
                'max:255',
            ],

            'price' => [
                'sometimes',
                'numeric',
                'min:0',
            ],

            'is_avialable' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}
