<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ExportOrdersRequest;
use App\Jobs\GenerateOrderExport;
use Illuminate\Http\JsonResponse;

class OrderExportController extends Controller
{
    public function export(
        ExportOrdersRequest $request
    ): JsonResponse {
        GenerateOrderExport::dispatch(
            $request->user()->id,
            $request->integer('restaurant_id'),
            $request->string('from')->toString(),
            $request->string('to')->toString(),
            $request->string('format')->toString(),
        );

        return response()->json([
            'message' => 'Order export has been queued.',
        ], 202);
    }
}