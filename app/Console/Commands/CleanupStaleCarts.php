<?php

namespace App\Console\Commands;

use App\Models\Cart;
use Illuminate\Console\Command;

class CleanupStaleCarts extends Command
{
    protected $signature = 'quickbite:cleanup-stale-carts';

    protected $description = 'Clean up stale carts';

    public function handle(): int
    {
        $deleted = Cart::where('updated_at', '<', now()->subHours(24))
            ->delete();

        $this->info("Deleted {$deleted} stale carts.");

        return self::SUCCESS;
    }
}