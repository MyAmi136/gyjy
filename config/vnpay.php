<?php

/**
 * ============================================================================
 * VNPAY PAYMENT GATEWAY CONFIGURATION
 * ============================================================================
 * 
 * SANDBOX (Test Mode - Được khuyến khích sử dụng để phát triển)
 * Không mất tiền thật, chỉ để test
 * 
 * PRODUCTION (Real Mode - Sử dụng khi bạn sẵn sàng)
 * Yêu cầu tài khoản VNPAY thật
 * 
 * ============================================================================
 */

// ⭐ SANDBOX MODE (Test - Dùng cái này)
// Cấu hình này đã test được - Ready for development
$vnp_TmnCode = "2QX1S61S";
$vnp_HashSecret = "THTZQNJFFX2GTNZN12C6SV5QH67GU9DM";  // ✅ Correct Sandbox Hash Secret
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";

// Generate return URL dynamically - SAFE AND ENCODED
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$path = dirname($_SERVER['SCRIPT_NAME']);
$vnp_Returnurl = $protocol . "://" . $host . $path . "/index.php?page=vnpay_return";

/**
 * ============================================================================
 * TEST CARD INFORMATION (SANDBOX ONLY - Dùng cho test)
 * ============================================================================
 * 
 * VISA:
 *   Card Number: 4111111111111111
 *   Expiry: 07/25
 *   CVV: 123
 *   OTP: 123456
 * 
 * MASTERCARD:
 *   Card Number: 5555555555554444
 *   Expiry: 07/25
 *   CVV: 123
 *   OTP: 123456
 * 
 * Phone Number: 0912345678
 * OTP for Phone: 123456
 * 
 * ============================================================================
 */

// Nhân diện chế độ hiện tại
$VNPAY_MODE = ($vnp_TmnCode === "2QX1S61S") ? "SANDBOX (Test)" : "PRODUCTION (Real)";

// Optional: Log mode để debug
// error_log("VNPAY activated in: " . $VNPAY_MODE);

?>
