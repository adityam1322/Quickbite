<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can(
            'manage',
            $this->route('order')
        );
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',
                'in:pending,confirmed,preparing,ready,out_for_delivery,delivered,cancelled',
            ],
        ];
    }
}