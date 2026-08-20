<?php

namespace App\Services\WhatsApp;

use App\Models\WaLog;

class WhatsAppNotificationService
{
    protected WhatsAppGatewayInterface $gateway;

    public function __construct(WhatsAppGatewayInterface $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Send notification message and store log.
     */
    public function send(string $recipientType, string $recipientName, ?string $rawPhone, string $notificationType, string $message, array $extraData = []): WaLog
    {
        if (empty($rawPhone) || trim($rawPhone) === '-' || trim($rawPhone) === '') {
            return WaLog::create([
                'penerima_tipe' => $recipientType,
                'penerima_nama' => $recipientName,
                'no_wa' => '-',
                'jenis_notifikasi' => $notificationType,
                'isi_pesan' => $message,
                'status' => 'failed',
                'error_message' => 'Nomor WhatsApp penerima belum diisi / kosong pada data profil.',
            ]);
        }

        $formattedPhone = $this->formatPhoneNumber($rawPhone);

        // Record initial log entry
        $log = WaLog::create([
            'penerima_tipe' => $recipientType,
            'penerima_nama' => $recipientName,
            'no_wa' => $formattedPhone,
            'jenis_notifikasi' => $notificationType,
            'isi_pesan' => $message,
            'status' => 'pending',
        ]);

        $result = $this->gateway->sendMessage($formattedPhone, $message, $extraData);

        if ($result['success']) {
            $log->update([
                'status' => 'sent',
                'gateway_response' => is_string($result['response']) ? $result['response'] : json_encode($result['response']),
            ]);
        } else {
            $log->update([
                'status' => 'failed',
                'gateway_response' => is_string($result['response']) ? $result['response'] : json_encode($result['response']),
                'error_message' => $result['error'],
            ]);
        }

        return $log;
    }

    /**
     * Format Indonesian phone numbers to standard 628xxx.
     */
    public function formatPhoneNumber(string $phone): string
    {
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($cleaned, '0')) {
            return '62' . substr($cleaned, 1);
        }

        if (str_starts_with($cleaned, '8')) {
            return '62' . $cleaned;
        }

        return $cleaned;
    }
}
