# QuickBite MCP Server

## Server

Handle:

mcp/food-delivery

## Transport

Streamable HTTP

## Endpoint

http://localhost:8000/mcp/food-delivery

## Authentication

The MCP server uses Laravel Sanctum authentication.

Send:

Authorization: Bearer <SANCTUM_TOKEN>

## Start MCP Inspector

php artisan mcp:inspector mcp/food-delivery

## Available Tools

- search_restaurants
- get_restaurant_menu
- get_order_status

## Prompt

- plan_meal

## Security

MCP tools respect the application's existing authentication
and authorization rules.

Customers cannot access another customer's order.

Sensitive information such as passwords, payment credentials,
card numbers, CVVs, and provider secrets is not logged.