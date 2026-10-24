<?php

/**
 * ============================================================================
 * MOCK PAYMENT GATEWAY - For demonstration & development
 * ============================================================================
 * 
 * Giả lập gateway thanh toán để test toàn bộ flow
 * Trong production, thay bằng VNPAY/PayOS thực
 * 
 * ============================================================================
 */

// Payment status options
define('PAYMENT_PENDING', 'pending');
define('PAYMENT_SUCCESS', 'success');
define('PAYMENT_FAILED', 'failed');

// Mock payment configuration using CONSTANTS
define('MOCK_PAYMENT_ENABLED', true);
define('MOCK_PAYMENT_NAME', 'Mock Payment Gateway');
define('MOCK_PAYMENT_SUCCESS_RATE', 0.9);  // 90% success
define('MOCK_PAYMENT_MIN_AMOUNT', 10000);  // 10,000 VND
define('MOCK_PAYMENT_MAX_AMOUNT', 100000000);  // 100 million VND

/**
 * Process mock payment
 * @param int $amount Amount in VND
 * @param int $order_id Order ID
 * @return array Payment result
 */
function processMockPayment($amount, $order_id) {
    
    try {
        // Validate amount
        if($amount < MOCK_PAYMENT_MIN_AMOUNT) {
            return array(
                'status' => PAYMENT_FAILED,
                'code' => 'AMOUNT_TOO_LOW',
                'message' => 'Số tiền quá thấp. Tối thiểu: ' . number_format(MOCK_PAYMENT_MIN_AMOUNT) . 'đ'
            );
        }
        
        if($amount > MOCK_PAYMENT_MAX_AMOUNT) {
            return array(
                'status' => PAYMENT_FAILED,
                'code' => 'AMOUNT_TOO_HIGH',
                'message' => 'Số tiền quá cao. Tối đa: ' . number_format(MOCK_PAYMENT_MAX_AMOUNT) . 'đ'
            );
        }
        
        // Simulate payment processing with random success/failure
        $random = mt_rand(0, 100) / 100;  // 0-1
        $is_success = $random < MOCK_PAYMENT_SUCCESS_RATE;
        
        // Generate transaction reference
        $txn_ref = "MOCK" . $order_id . date('YmdHis');
        
        if($is_success) {
            return array(
                'status' => PAYMENT_SUCCESS,
                'code' => '00',
                'message' => 'Thanh toán thành công',
                'transaction_id' => $txn_ref,
                'order_id' => $order_id,
                'amount' => $amount,
                'timestamp' => date('Y-m-d H:i:s')
            );
        } else {
            return array(
                'status' => PAYMENT_FAILED,
                'code' => '99',
                'message' => 'Thanh toán thất bại (Demo)',
                'transaction_id' => $txn_ref,
                'order_id' => $order_id
            );
        }
        
    } catch (Exception $e) {
        error_log("Mock Payment Error: " . $e->getMessage());
        return array(
            'status' => PAYMENT_FAILED,
            'code' => 'ERROR',
            'message' => $e->getMessage()
        );
    }
}

?>

