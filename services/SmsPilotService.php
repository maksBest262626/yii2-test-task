<?php

namespace app\services;

use Yii;

class SmsPilotService
{
    public function __construct(private readonly string $apiKey) {}

    public function send(string $phone, string $message): bool
    {
        if (empty($this->apiKey)) {
            Yii::warning('SmsPilotService: API key not configured', 'sms');
            return false;
        }

        $url = 'https://smspilot.ru/api.php?' . http_build_query([
            'send'   => $message,
            'to'     => $phone,
            'apikey' => $this->apiKey,
            'from'   => 'INFORM',
            'format' => 'json',
        ]);

        $ch = curl_init($url);
        if ($ch === false) {
            Yii::error('SmsPilotService: Failed to initialize cURL', 'sms');
            return false;
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false) {
            Yii::error("SmsPilotService: cURL error for phone {$phone}: {$curlError}", 'sms');
            return false;
        }

        if ($httpCode !== 200) {
            Yii::error("SmsPilotService: HTTP {$httpCode} for phone {$phone}", 'sms');
            return false;
        }

        $data = json_decode($response, true);
        if (isset($data['error'])) {
            Yii::error("SmsPilotService: API error for phone {$phone}: " . json_encode($data['error']), 'sms');
            return false;
        }

        return true;
    }
}