<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDeliveryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can(
            'update',
            $this->route('delivery')
        );
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',
                'in:assigned,picked_up,out_for_delivery,delivered,failed',
            ],

            'latitude' => [
                'sometimes',
                'nullable',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'sometimes',
                'nullable',
                'numeric',
                'between:-180,180',
            ],
        ];
    }
}