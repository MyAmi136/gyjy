<?php

/**
 * ============================================================================
 * PAYOS WEBHOOK HANDLER
 * ============================================================================
 *
 * Endpoint để PayOS gửi thông báo thanh toán
 * URL: https://yoursite.com/webhook/payos.php
 *
 * ============================================================================
 */

if (file_exists(dirname(__DIR__) . '/config/payos.php')) {
    require_once dirname(__DIR__) . '/config/payos.php';
}

require_once dirname(__DIR__) . '/model/Order.php';

function getPayOSWebhookData($data)
{
    if (!is_array($data)) {
        return [];
    }

    if (isset($data['data']) && is_array($data['data'])) {
        return $data['data'];
    }

    return $data;
}

function buildPayOSSignatureData($data)
{
    $payload = getPayOSWebhookData($data);
    $fields = ['orderCode', 'amount', 'description', 'accountNumber', 'referenceCode', 'transactionDateTime'];
    $parts = [];

    foreach ($fields as $field) {
        if (array_key_exists($field, $payload) && $payload[$field] !== null && $payload[$field] !== '') {
            $parts[] = (string) $payload[$field];
        }
    }

    return implode('|', $parts);
}

// 🔐 Verify webhook signature
function verifyPayOSSignature($data, $signature)
{
    if (!defined('PAYOS_CHECKSUM_KEY') || PAYOS_CHECKSUM_KEY === '' || PAYOS_CHECKSUM_KEY === 'YOUR_CHECKSUM_KEY') {
        return false;
    }

    $signature = trim((string) $signature);
    if ($signature === '') {
        return false;
    }

    $expectedSignature = hash_hmac('sha256', buildPayOSSignatureData($data), PAYOS_CHECKSUM_KEY);
    return hash_equals($expectedSignature, $signature);
}

// ✅ Process webhook
try {
    $payload = file_get_contents('php://input');
    if ($payload === false || trim($payload) === '') {
        http_response_code(400);
        echo json_encode(['error' => 'Empty payload']);
        exit;
    }

    $data = json_decode($payload, true);
    if (!is_array($data)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON payload']);
        exit;
    }

    $normalizedData = getPayOSWebhookData($data);

    error_log('PayOS Webhook Received: ' . json_encode($data));

    $signature = $_SERVER['HTTP_X_PAYOS_SIGNATURE'] ?? $_SERVER['HTTP_X_SIGNATURE'] ?? '';

    if (!verifyPayOSSignature($normalizedData, $signature)) {
        error_log('PayOS Webhook Signature Invalid!');
        http_response_code(401);
        echo json_encode(['error' => 'Invalid signature']);
        exit;
    }

    $orderCode = $normalizedData['orderCode'] ?? '';
    $orderModel = new Order();

    error_log('PayOS payment processed: ' . $orderCode);

    http_response_code(200);
    echo json_encode([
        'code' => 'OK',
        'message' => 'Received'
    ]);
} catch (Exception $e) {
    error_log('PayOS Webhook Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

