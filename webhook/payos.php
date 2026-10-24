<?php
require_once dirname(__DIR__) . '/model/PayOS.php';
require_once dirname(__DIR__) . '/model/Payment.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$requestBody = file_get_contents('php://input');
$webhook = is_string($requestBody) ? json_decode($requestBody, true) : null;
if (!is_array($webhook) || !is_array($webhook['data'] ?? null)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid webhook payload']);
    exit;
}

$data = $webhook['data'];
if (!PayOS::verifyWebhookSignature($data, $webhook['signature'] ?? '')) {
    error_log('PayOS webhook rejected: invalid signature');
    http_response_code(401);
    echo json_encode(['error' => 'Invalid signature']);
    exit;
}

if (($webhook['success'] ?? false) !== true || (string) ($webhook['code'] ?? '') !== '00') {
    http_response_code(200);
    echo json_encode(['code' => 'OK', 'message' => 'Payment not completed']);
    exit;
}

$orderId = filter_var($data['orderCode'] ?? null, FILTER_VALIDATE_INT);
$amount = filter_var($data['amount'] ?? null, FILTER_VALIDATE_INT);
if ($orderId === false || $orderId < 1 || $amount === false || $amount < 1) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid order or amount']);
    exit;
}

try {
    $transactionCode = (string) ($data['reference'] ?? $data['paymentLinkId'] ?? '');
    (new Payment())->markPayOSOrderPaid($orderId, $amount, $transactionCode);
    http_response_code(200);
    echo json_encode(['code' => 'OK', 'message' => 'Payment recorded']);
} catch (Throwable $exception) {
    error_log('PayOS webhook processing failed: ' . $exception->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Unable to process payment']);
}
