<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Promptable;
use Stringable;

class TestOpenAI extends Command
{
    protected $signature = 'ai:test';

    protected $description = 'Test Gemini AI connection';

    public function handle(): int
    {
        try {
            $agent = new class implements Agent
            {
                use Promptable;

                public function instructions(): Stringable|string
                {
                    return 'Answer briefly.';
                }
            };

            $response = $agent->prompt(
                'Say hello in one short sentence.'
            );

            $this->info('AI RESPONSE:');
            $this->line((string) $response);

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('AI ERROR:');
            $this->error(get_class($e));
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }
}