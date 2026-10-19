<?php

namespace App\Mcp\Prompts;

use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Prompt;
use Laravel\Mcp\Server\Prompts\Argument;

#[Description('Create a meal plan using available QuickBite restaurants and menu items.')]
class PlanMeal extends Prompt
{
    /**
     * Handle the prompt request.
     */
    public function handle(Request $request): Response
    {
        $preferences = $request->get('preferences') ?? 'No specific preferences provided.';
        $budget = $request->get('budget') ?? 'No budget specified.';
        $city = $request->get('city') ?? 'No city specified.';

        return Response::text(
            "Create a meal plan for the QuickBite user.\n\n"
            . "Food preferences: {$preferences}\n"
            . "Budget: {$budget}\n"
            . "City: {$city}\n\n"
            . "Rules:\n"
            . "- Use only restaurants and menu items available through QuickBite.\n"
            . "- Do not invent restaurant or menu information.\n"
            . "- Do not create, modify, cancel, refund, checkout, or pay for an order.\n"
            . "- Do not bypass application validation or authorization.\n"
            . "- Treat backend tool results as the source of truth.\n"
            . "- Keep the meal plan within the requested budget when verified data allows it.\n"
            . "- Clearly identify any information that cannot be verified."
        );
    }

    /**
     * Get the prompt's arguments.
     *
     * @return array<int, Argument>
     */
    public function arguments(): array
    {
        return [
            new Argument(
                name: 'preferences',
                description: 'Food preferences such as vegetarian, spicy, high-protein, or cuisine preference.',
                required: false,
            ),

            new Argument(
                name: 'budget',
                description: 'Maximum meal budget requested by the user.',
                required: false,
            ),

            new Argument(
                name: 'city',
                description: 'City where the user wants to find food.',
                required: false,
            ),
        ];
    }
}