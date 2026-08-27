<?php

namespace App\Services\WhatsApp;

use Illuminate\Support\Facades\Log;

/**
 * FUNGSI FILE INI:
 * Menulis simulasi pengiriman pesan WhatsApp ke file log (laravel.log) untuk pengujian lokal tanpa API Fonnte.
 */
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
