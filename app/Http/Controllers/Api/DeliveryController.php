<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateDeliveryRequest;
use App\Models\Delivery;
use Illuminate\Http\JsonResponse;

class DeliveryController extends Controller
{
    public function index(): JsonResponse
    {
        $user = request()->user();

        if ($user->hasRole('administrator')) {
            $deliveries = Delivery::query()
                ->latest()
                ->get();
        } else {
            $deliveries = Delivery::query()
                ->where('delivery_partner_id', $user->id)
                ->latest()
                ->get();
        }

        return response()->json([
            'deliveries' => $deliveries,
        ]);
    }

    public function show(Delivery $delivery): JsonResponse
    {
        $this->authorize('view', $delivery);

        return response()->json([
            'delivery' => $delivery,
        ]);
    }

    public function update(
        UpdateDeliveryRequest $request,
        Delivery $delivery
    ): JsonResponse {
        $delivery->update(
            $request->validated()
        );

        return response()->json([
            'message' => 'Delivery updated successfully.',
            'delivery' => $delivery->fresh(),
        ]);
    }
}