<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use Illuminate\Http\JsonResponse;

class RefundController extends Controller
{
    public function index(): JsonResponse
    {
        $refunds = Refund::query()
            ->latest()
            ->paginate(20);

        return response()->json([
            'data' => $refunds,
        ]);
    }

    public function show(Refund $refund): JsonResponse
    {
        return response()->json([
            'data' => $refund,
        ]);
    }
}