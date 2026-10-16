<?php

namespace App\Services\Sms;

use App\Contracts\SmsProvider;
use Illuminate\Support\Facades\Log;

class FakeSmsProvider implements SmsProvider
{
    public function send(
        string $phone,
        string $message
    ): bool {
        Log::info('Fake SMS sent.', [
            'phone' => $phone,
            'message' => $message,
        ]);

        return true;
    }
}