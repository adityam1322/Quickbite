
<?php

use App\Ai\Agents\FoodDiscoveryAgent;
use App\Ai\Tools\SearchRestaurants;
use App\Ai\Tools\SearchMenuItems;
use App\Ai\Tools\EstimateOrderTotal;
use App\Ai\Tools\CheckRestaurantAvailability;

it('returns successful structured output', function () {
    FoodDiscoveryAgent::fake([
        [
            'suggestions' => [
                [
                    'restaurant_id' => 1,
                    'menu_item_ids' => [10, 11],
                    'explanation' => 'Vegetarian food within the requested budget.',
                    'uncertainty' => 0.1,
                ],
            ],
        ],
    ]);

    $agent = new FoodDiscoveryAgent();

    $response = $agent->prompt(
        'Suggest vegetarian food under 300 rupees'
    );

    expect($response)->not->toBeNull();
});

it('does not suggest unavailable menu items', function () {
    FoodDiscoveryAgent::fake([
        [
            'suggestions' => [
                [
                    'restaurant_id' => 1,
                    'menu_item_ids' => [10],
                    'explanation' => 'Only available vegetarian item is suggested.',
                    'uncertainty' => 0.1,
                ],
            ],
        ],
    ]);

    $agent = new FoodDiscoveryAgent();

    $response = $agent->prompt(
        'Suggest vegetarian food under 300 rupees'
    );

    expect($response)->not->toBeNull();
});

it('only exposes authorized read-only tools', function () {
    $agent = new FoodDiscoveryAgent();

    $tools = iterator_to_array($agent->tools());

    $toolNames = array_map(
        fn ($tool) => $tool::class,
        $tools
    );

    expect($toolNames)->toBe([
        SearchRestaurants::class,
        SearchMenuItems::class,
        EstimateOrderTotal::class,
        CheckRestaurantAvailability::class,
    ]);
});

it('handles malformed structured output', function () {
    FoodDiscoveryAgent::fake([
        [
            'invalid_field' => 'invalid response',
        ],
    ]);

    $agent = new FoodDiscoveryAgent();

    $response = $agent->prompt(
        'Suggest vegetarian food under 300 rupees'
    );

    expect($response)->not->toBeNull();
});
