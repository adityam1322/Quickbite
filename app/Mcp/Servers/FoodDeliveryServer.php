<?php

namespace App\Mcp\Servers;

use App\Mcp\Tools\SearchRestaurants;
use App\Mcp\Tools\GetRestaurantMenu;
use App\Mcp\Tools\GetOrderStatus;
use App\Mcp\Tools\SearchMenuItems;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;
use App\Mcp\Resources\MenuResource;
use App\Mcp\Resources\RestaurantResource;
use App\Mcp\Prompts\PlanMeal;

#[Name('Food Delivery Server')]
#[Version('0.0.1')]
#[Instructions('Read-only food delivery server for restaurant, menu, and order information.')]
class FoodDeliveryServer extends Server
{
    protected array $tools = [
        SearchRestaurants::class,
        GetRestaurantMenu::class,
        GetOrderStatus::class,
        SearchMenuItems::class,

    ];

    protected array $resources = [
        RestaurantResource::class,
        MenuResource::class,
    ];

    protected array $prompts = [
        PlanMeal::class,
    ];
}