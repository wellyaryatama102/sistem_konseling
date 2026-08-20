<?php

namespace App\Services\WhatsApp;

interface WhatsAppGatewayInterface
{
    /**
     * Send a WhatsApp text message.
     *
     * @param string $recipientNumber
     * @param string $message
     * @param array $extraData
     * @return array ['success' => bool, 'response' => mixed, 'error' => string|null]
     */
    public function sendMessage(string $recipientNumber, string $message, array $extraData = []): array;
}
