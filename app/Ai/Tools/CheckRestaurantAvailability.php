<?php

namespace App\Ai\Tools;

use App\Models\Restaurant;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Tools\Request;
use Laravel\Ai\Contracts\Tool;
use Stringable;

class CheckRestaurantAvailability implements Tool
{
    /**
     * Describe what this tool does.
     */
    public function description(): Stringable|string
    {
        return 'Check whether a restaurant is currently open and available for orders.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $restaurantId = $request['restaurant_id'];

        $restaurant = Restaurant::query()
            ->select([
                'id',
                'name',
                'is_active',
            ])
            ->with([
                'hours',
            ])
            ->find($restaurantId);

        if (! $restaurant) {
            return json_encode([
                'available' => false,
                'reason' => 'Restaurant not found.',
            ]);
        }

        if (! $restaurant->is_active) {
            return json_encode([
                'restaurant_id' => $restaurant->id,
                'restaurant_name' => $restaurant->name,
                'available' => false,
                'reason' => 'Restaurant is inactive.',
            ]);
        }

        $now = now();

        $today = $now->dayOfWeek;
        $currentTime = $now->format('H:i:s');

        $hours = $restaurant->hours
            ->firstWhere('day_of_week', $today);

        if (! $hours) {
            return json_encode([
                'restaurant_id' => $restaurant->id,
                'restaurant_name' => $restaurant->name,
                'available' => false,
                'reason' => 'No opening hours configured for today.',
            ]);
        }

        if ($hours->is_closed) {
            return json_encode([
                'restaurant_id' => $restaurant->id,
                'restaurant_name' => $restaurant->name,
                'available' => false,
                'reason' => 'Restaurant is closed today.',
            ]);
        }

        $isOpen = $currentTime >= $hours->open_time
            && $currentTime <= $hours->close_time;

        return json_encode([
            'restaurant_id' => $restaurant->id,
            'restaurant_name' => $restaurant->name,
            'available' => $isOpen,
            'reason' => $isOpen
                ? 'Restaurant is currently open.'
                : 'Restaurant is currently closed.',
            'open_time' => $hours->open_time,
            'close_time' => $hours->close_time,
        ]);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'restaurant_id' => $schema->integer()->required(),
        ];
    }
}