<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateOrderExport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderExportController extends Controller
{
    public function export(Request $request): JsonResponse
    {
        GenerateOrderExport::dispatch(
            $request->user()->id
        );

        return response()->json([
            'message' => 'Order export has been queued.',
        ]);
    }
}