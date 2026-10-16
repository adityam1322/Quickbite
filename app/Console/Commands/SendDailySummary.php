<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class SendDailySummary extends Command
{
    protected $signature = 'quickbite:send-daily-summary';

    protected $description = 'Generate the daily order summary';

    public function handle(): int
    {
        $orders = Order::whereDate(
            'created_at',
            now()->subDay()->toDateString()
        )->get();

        $this->info(
            "Daily summary: {$orders->count()} orders."
        );

        return self::SUCCESS;
    }
}