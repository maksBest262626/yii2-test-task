<?php

namespace app\services;

use Yii;

class SmsPilotService
{
    public function send(string $phone, string $message): bool
    {
        $apiKey = Yii::$app->params['smsPilotApiKey'] ?? '';
        if (empty($apiKey)) {
            Yii::warning('SmsPilotService: API key not configured', 'sms');
            return false;
        }

        $url = 'https://smspilot.ru/api.php?' . http_build_query([
            'send' => $message,
            'to' => $phone,
            'apikey' => $apiKey,
            'from' => 'INFORM',
            'format' => 'json',
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || $response === false) {
            Yii::error("SmsPilotService: HTTP error {$httpCode} for phone {$phone}", 'sms');
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
