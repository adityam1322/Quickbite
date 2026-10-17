<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Configuration::query()
                ->orderBy('key')
                ->get(),
        ]);
    }

    public function update(
        Request $request,
        Configuration $configuration
    ): JsonResponse {
        $validated = $request->validate([
            'value' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $configuration->update($validated);

        return response()->json([
            'message' => 'Configuration updated successfully.',
            'data' => $configuration->fresh(),
        ]);
    }
}