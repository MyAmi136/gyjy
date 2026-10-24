<?php
require_once __DIR__ . '/../config/payos.php';

class PayOS
{
    private function ensureConfigured()
    {
        if (
            PAYOS_CLIENT_ID === '' ||
            PAYOS_API_KEY === '' ||
            PAYOS_CHECKSUM_KEY === '' ||
            PAYOS_RETURN_URL === '' ||
            PAYOS_CANCEL_URL === ''
        ) {
            throw new RuntimeException('PayOS chưa được cấu hình đầy đủ.');
        }

        if (!function_exists('curl_init')) {
            throw new RuntimeException('PHP cURL chưa được bật.');
        }
    }

    public function createPaymentLink($orderId, $amount)
    {
        $this->ensureConfigured();

        $amount = (int) round((float) $amount);
        if ($orderId < 1 || $amount < 1) {
            throw new InvalidArgumentException('Mã đơn hàng hoặc số tiền không hợp lệ.');
        }

        $orderCode = (int) $orderId;
        $description = 'Thanh toan don ' . $orderCode;
        if (strlen($description) > 25) {
            throw new InvalidArgumentException('Mã đơn hàng vượt quá giới hạn của PayOS.');
        }

        $returnUrl = $this->appendOrderId(PAYOS_RETURN_URL, $orderId);
        $cancelUrl = $this->appendOrderId(PAYOS_CANCEL_URL, $orderId);
        $signatureData = [
            'amount' => $amount,
            'cancelUrl' => $cancelUrl,
            'description' => $description,
            'orderCode' => $orderCode,
            'returnUrl' => $returnUrl,
        ];
        ksort($signatureData);
        $signature = [];
        foreach ($signatureData as $key => $value) {
            $signature[] = $key . '=' . $value;
        }

        $requestBody = [
            'orderCode' => $orderCode,
            'amount' => $amount,
            'description' => $description,
            'returnUrl' => $returnUrl,
            'cancelUrl' => $cancelUrl,
            'signature' => hash_hmac('sha256', implode('&', $signature), PAYOS_CHECKSUM_KEY),
        ];

        $curl = curl_init('https://api-merchant.payos.vn/v2/payment-requests');
        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'x-client-id: ' . PAYOS_CLIENT_ID,
                'x-api-key: ' . PAYOS_API_KEY,
            ],
            CURLOPT_POSTFIELDS => json_encode($requestBody, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]);

        $responseBody = curl_exec($curl);
        $httpStatus = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        curl_close($curl);

        if ($responseBody === false) {
            throw new RuntimeException('Không thể kết nối PayOS: ' . $curlError);
        }

        $response = json_decode($responseBody, true);
        if (
            $httpStatus < 200 ||
            $httpStatus >= 300 ||
            !is_array($response) ||
            (string) ($response['code'] ?? '') !== '00' ||
            empty($response['data']['checkoutUrl'])
        ) {
            $message = is_array($response) ? ($response['desc'] ?? 'PayOS từ chối tạo liên kết thanh toán.') : 'Phản hồi PayOS không hợp lệ.';
            throw new RuntimeException((string) $message);
        }

        return $response['data'];
    }

    public static function verifyWebhookSignature(array $data, $signature)
    {
        if (PAYOS_CHECKSUM_KEY === '' || !is_string($signature) || $signature === '') {
            return false;
        }

        $keys = array_keys($data);
        sort($keys, SORT_STRING);
        $parts = [];
        foreach ($keys as $key) {
            $value = $data[$key];
            if (is_array($value)) {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            } elseif ($value === null) {
                $value = 'null';
            } elseif (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }
            $parts[] = $key . '=' . $value;
        }

        $expected = hash_hmac('sha256', implode('&', $parts), PAYOS_CHECKSUM_KEY);
        return hash_equals($expected, $signature);
    }

    private function appendOrderId($url, $orderId)
    {
        $separator = strpos($url, '?') === false ? '?' : '&';
        return $url . $separator . 'order_id=' . rawurlencode((string) $orderId);
    }
}
