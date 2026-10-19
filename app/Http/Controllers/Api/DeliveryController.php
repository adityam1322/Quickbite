<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateDeliveryRequest;
use App\Models\DeliveryAssignment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UpdateDeliveryAvailabilityRequest;

class DeliveryController extends Controller
{
    public function index(): JsonResponse
    {
        $user = request()->user();

        if ($user->hasRole('administrator')) {
            $deliveries = DeliveryAssignment::query()
                ->latest()
                ->get();
        } else {
            $deliveries = DeliveryAssignment::query()
                ->where('delivery_partner_id', $user->id)
                ->latest()
                ->get();
        }

        return response()->json([
            'deliveries' => $deliveries,
        ]);
    }

    public function show(DeliveryAssignment $delivery): JsonResponse
    {
        Gate::authorize('view', $delivery);

        return response()->json([
            'delivery' => $delivery,
        ]);
    }

    public function update(
        UpdateDeliveryRequest $request,
        DeliveryAssignment $delivery
    ): JsonResponse {
        $delivery->update(
            $request->validated()
        );

        return response()->json([
            'message' => 'Delivery updated successfully.',
            'delivery' => $delivery->fresh(),
        ]);
    }

    public function updateAvailability(
        UpdateDeliveryAvailabilityRequest $request
    ): JsonResponse {
        $user = $request->user();

        $profile = $user->deliveryPartnerProfile;

        if (! $profile) {
            return response()->json([
                'message' => 'Delivery partner profile not found.',
            ], 404);
        }

        $profile->update([
            'is_available' => $request->boolean('is_available'),
        ]);

        return response()->json([
            'message' => 'Delivery availability updated successfully.',
            'is_available' => $profile->is_available,
        ]);
    }
}