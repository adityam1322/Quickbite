<?php

namespace App\Services;

use App\Models\McpToolLog;
use Illuminate\Support\Facades\Auth;

class McpToolLogger
{
    /**
     * Log an MCP tool execution.
     */
    public function log(
        string $toolName,
        int $durationMs,
        string $outcome
    ): McpToolLog {
        return McpToolLog::create([
            'user_id' => Auth::id(),
            'tool_name' => $toolName,
            'duration_ms' => $durationMs,
            'outcome' => $outcome,
        ]);
    }
}