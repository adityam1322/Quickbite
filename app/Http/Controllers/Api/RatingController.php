<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRatingRequest;
use App\Models\Order;
use App\Models\Rating;
use Illuminate\Http\JsonResponse;

class RatingController extends Controller
{
    public function store(
        StoreRatingRequest $request,
        Order $order
    ): JsonResponse {
        
        // 1. Order customer ka hi hona chahiye
        if ($order->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'You cannot rate this order.',
            ], 403);
        }

        // 2. Rating sirf delivered order par allowed hai
        if ($order->order_status !== 'delivered') {
            return response()->json([
                'message' => 'Order must be delivered before rating.',
            ], 422);
        }

        // 3. Check: already rated?
        $alreadyRated = Rating::query()
            ->where('order_id', $order->id)
            ->where('user_id', $request->user()->id)
            ->exists();

        if ($alreadyRated) {
            return response()->json([
                'message' => 'You have already rated this order.',
            ], 422);
        }

        // 4. Create rating
        $rating = Rating::create([
            'user_id' => $request->user()->id,
            'restaurant_id' => $order->restaurant_id,
            'order_id' => $order->id,
            'rating' => $request->integer('rating'),
            'review' => $request->input('review'),
        ]);

        return response()->json([
            'message' => 'Rating submitted successfully.',
            'rating' => $rating,
        ], 201);
    }
}