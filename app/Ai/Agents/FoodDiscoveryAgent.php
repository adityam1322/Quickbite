<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Laravel\Ai\Providers\Tools\ProviderTool;
use App\Ai\Tools\SearchRestaurants;
use App\Ai\Tools\SearchMenuItems;
use App\Ai\Tools\EstimateOrderTotal;
use App\Ai\Tools\CheckRestaurantAvailability;
use Laravel\Ai\Attributes\MaxSteps;

use Stringable;

#[MaxSteps(4)]
class FoodDiscoveryAgent implements Agent, Conversational, HasStructuredOutput, HasTools
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return <<<PROMPT
           You are FoodDiscoveryAgent for the QuickBite food delivery application.
           
           Your job is to help users discover restaurants and menu items based on
           their food preferences, budget, and location-related request.
           
           Use only the available read-only tools to retrieve restaurant, menu,
           availability, and estimated cart information.
           
           Never create, update, delete, cancel, refund, checkout, or pay for an order.
           
           Never make final pricing, coupon, payment, or order-status decisions.
           
           Use backend tool results as the source of truth for restaurant and menu data.
           
           Return only the information supported by the tool results.
           
           If the requested information cannot be verified, clearly express uncertainty.
           
           Do not expose private user information, payment information, secrets,
           API keys, or unnecessary personal data.
           
           Keep tool usage bounded and avoid unnecessary tool calls.
        PROMPT;
    }

    /**
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return [];
    }

    /**
     * Get the tools available to the agent.
     *
     * @return list<Agent|Tool|ProviderTool>
     */
    public function tools(): iterable
    {
        return [
            new SearchRestaurants(),
            new SearchMenuItems(),
            new EstimateOrderTotal(),
            new CheckRestaurantAvailability(),
        ];
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'suggestions' => $schema->array()->items(
                $schema->object([
                    'restaurant_id' => $schema->integer()->required(),

                    'menu_item_ids' => $schema->array()->items(
                        $schema->integer()
                    )->required(),

                    'explanation' => $schema->string()->required(),

                    'uncertainty' => $schema->number()->required(),
                ])
            )->required(),
        ];
    }
}
