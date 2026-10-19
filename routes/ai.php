<?php

use App\Mcp\Servers\FoodDeliveryServer;
use Laravel\Mcp\Facades\Mcp;

Mcp::web('/mcp/food-delivery', FoodDeliveryServer::class)
    ->middleware('auth:sanctum');