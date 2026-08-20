<?php

namespace App\Services\WhatsApp;

use Illuminate\Support\Facades\Log;

class LogWhatsAppGateway implements WhatsAppGatewayInterface
{
    public function sendMessage(string $recipientNumber, string $message, array $extraData = []): array
    {
        Log::info("[SIMULATED WA GATEWAY] Sent to: {$recipientNumber} | Message: {$message}", $extraData);

        return [
            'success' => true,
            'response' => 'SIMULATED_LOG_SUCCESS',
            'error' => null,
        ];
    }
}
