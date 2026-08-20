<?php

namespace App\Services\WhatsApp;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteWhatsAppGateway implements WhatsAppGatewayInterface
{
    protected string $url;
    protected string $token;

    public function __construct()
    {
        $this->url = config('services.fonnte.url', 'https://api.fonnte.com/send');
        $this->token = trim(config('services.fonnte.token', ''));
    }

    public function sendMessage(string $recipientNumber, string $message, array $extraData = []): array
    {
        if (empty($this->token)) {
            return [
                'success' => false,
                'response' => null,
                'error' => 'Token WhatsApp Gateway (Fonnte) belum dikonfigurasi di file .env.',
            ];
        }

        try {
            $payload = [
                'target' => $recipientNumber,
                'message' => $message,
                'countryCode' => '62',
            ];

            if (!empty($extraData['url'])) {
                $payload['url'] = $extraData['url'];
            }

            $response = Http::withoutVerifying()
                ->timeout(15)
                ->withHeaders([
                    'Authorization' => $this->token,
                ])
                ->post($this->url, $payload);

            $body = $response->json();

            if ($response->successful() && isset($body['status']) && $body['status'] === true) {
                return [
                    'success' => true,
                    'response' => $body,
                    'error' => null,
                ];
            }

            $errorMsg = $body['reason'] ?? ($body['detail'] ?? ($body['message'] ?? 'Gagal terkirim dari WhatsApp Gateway'));

            return [
                'success' => false,
                'response' => $body ?? $response->body(),
                'error' => is_string($errorMsg) ? $errorMsg : json_encode($errorMsg),
            ];
        } catch (\Throwable $e) {
            Log::error('FonnteWhatsAppGateway Exception: ' . $e->getMessage());

            return [
                'success' => false,
                'response' => null,
                'error' => $e->getMessage(),
            ];
        }
    }
}
