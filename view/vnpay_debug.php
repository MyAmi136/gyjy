<?php
/**
 * VNPAY DEBUG PAGE
 * Dùng để kiểm tra chi tiết các tham số gửi sang VNPAY
 * 
 * Để enable debug:
 * 1. Mở file controller/OrderController.php, hàm vnpay()
 * 2. Tìm dòng: // echo "<pre>";
 * 3. Uncomment 3 dòng sau để hiển thị debug
 * 4. Làm một transaction
 * 5. Script sẽ dừng và hiển thị $inputData
 */

// Kiểm tra logs của VNPAY
$log_file = "C:\\xampp\\logs\\php_error.log";

if(!file_exists($log_file)){
    echo "❌ Log file not found: " . $log_file;
    exit;
}

// Lấy 80 dòng cuối cùng từ log
$logs = shell_exec("powershell -NoProfile -Command \"Get-Content -Path '" . $log_file . "' -Tail 80 -Encoding UTF8\"");

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VNPAY Debug - Kiểm tra Tham số</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; padding: 20px; }
        .card { margin-bottom: 20px; }
        .log-content { 
            background-color: #1e1e1e; 
            color: #00ff00; 
            padding: 15px; 
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            max-height: 500px;
            overflow-y: auto;
            font-size: 12px;
            line-height: 1.4;
        }
        .highlight-vnpay { 
            background-color: #333;
            border-left: 3px solid #ffc107;
            padding: 5px;
            margin: 5px 0;
        }
        .error-log { color: #ff6b6b; }
        .success-log { color: #51cf66; }
        .warning-log { color: #ffd93d; }
    </style>
</head>
<body>

<div class="container-lg">
    <h1 class="mb-4">
        <i class="bi bi-bug"></i> VNPAY Debug Helper
    </h1>

    <!-- INSTRUCTIONS -->
    <div class="alert alert-info">
        <h5>📋 Hướng dẫn:</h5>
        <ol>
            <li>Mở <code>controller/OrderController.php</code>, tìm hàm <code>vnpay()</code></li>
            <li>Tìm dòng Comment: <code>// echo "&lt;pre&gt;";</code></li>
            <li>Bỏ comment 3 dòng tiếp theo (uncomment)</li>
            <li>Làm một giao dịch test ở trang checkout</li>
            <li>Script sẽ hiển thị var_dump($inputData) - đó là dữ liệu gửi sang VNPAY</li>
            <li>So sánh với log bên dưới để diagnose vấn đề</li>
        </ol>
    </div>

    <!-- REQUEST PARAMETERS CHECKLIST -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">✅ Kiểm tra Tham số VNPAY Request</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tham số</th>
                        <th>Yêu cầu</th>
                        <th>Ví dụ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>vnp_Version</code></td>
                        <td>Phải = <code>2.1.0</code></td>
                        <td>2.1.0</td>
                    </tr>
                    <tr>
                        <td><code>vnp_TmnCode</code></td>
                        <td>Phải = <code>2QX1S61S</code></td>
                        <td>2QX1S61S</td>
                    </tr>
                    <tr>
                        <td><code>vnp_Amount</code></td>
                        <td>Số nguyên (nhân 100), không dấu chấm</td>
                        <td>1000000 (= 10,000 VND)</td>
                    </tr>
                    <tr>
                        <td><code>vnp_Command</code></td>
                        <td>Phải = <code>pay</code></td>
                        <td>pay</td>
                    </tr>
                    <tr>
                        <td><code>vnp_CreateDate</code></td>
                        <td>Format <code>YmdHis</code> (không dấu gạch)</td>
                        <td>20260317160400</td>
                    </tr>
                    <tr>
                        <td><code>vnp_CurrCode</code></td>
                        <td>Phải = <code>VND</code></td>
                        <td>VND</td>
                    </tr>
                    <tr>
                        <td><code>vnp_OrderInfo</code></td>
                        <td>Ngắn gọn, không chứa ký tự lạ</td>
                        <td>Thanh toan don hang #123</td>
                    </tr>
                    <tr>
                        <td><code>vnp_TxnRef</code></td>
                        <td>Unique trong ngày, max 40 ký tự</td>
                        <td>ORD123202603171604</td>
                    </tr>
                    <tr>
                        <td><code>vnp_SecureHash</code></td>
                        <td>SHA512 HMAC (case-sensitive)</td>
                        <td>abc...xyz (64 ký tự hex)</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- RETURN VERIFICATION CHECKLIST -->
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">✅ Kiểm tra Xác Minh Hash (VNPAY Return)</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Bước</th>
                        <th>Mô tả</th>
                        <th>Giá trị</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1️⃣</td>
                        <td>Lấy <code>vnp_SecureHash</code> từ return</td>
                        <td>Từ $_GET['vnp_SecureHash']</td>
                    </tr>
                    <tr>
                        <td>2️⃣</td>
                        <td>Xóa vnp_SecureHash khỏi $_GET</td>
                        <td>unset($_GET['vnp_SecureHash'])</td>
                    </tr>
                    <tr>
                        <td>3️⃣</td>
                        <td>ksort() tất cả parameters</td>
                        <td>Sắp xếp alphabetically</td>
                    </tr>
                    <tr>
                        <td>4️⃣</td>
                        <td>Tạo hashdata = urlencode cả tên + giá trị</td>
                        <td>&vnp_Amount=...&vnp_Command=...</td>
                    </tr>
                    <tr>
                        <td>5️⃣</td>
                        <td>Tính hash = hash_hmac('sha512', hashdata, secret)</td>
                        <td>Phải khớp với vnp_SecureHash</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- PHP ERROR LOG -->
    <div class="card">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">📋 PHP Error Logs (80 dòng cuối)</h5>
        </div>
        <div class="card-body">
            <div class="log-content">
                <?php 
                if($logs){
                    $lines = explode("\n", trim($logs));
                    // Highlight dòng liên quan VNPAY
                    foreach($lines as $line){
                        if(strpos($line, 'VNPAY') !== false || strpos($line, 'Hash') !== false || strpos($line, 'Payment') !== false){
                            echo '<div class="highlight-vnpay">';
                            if(strpos($line, 'ERROR') !== false || strpos($line, 'FAILED') !== false){
                                echo '<span class="error-log">' . htmlspecialchars($line) . '</span>';
                            } elseif(strpos($line, 'Success') !== false || strpos($line, 'marked PAID') !== false){
                                echo '<span class="success-log">' . htmlspecialchars($line) . '</span>';
                            } else {
                                echo '<span class="warning-log">' . htmlspecialchars($line) . '</span>';
                            }
                            echo '</div>';
                        } else {
                            echo htmlspecialchars($line) . "<br>";
                        }
                    }
                } else {
                    echo "❌ Không có log! Chạy một giao dịch test trước.";
                }
                ?>
            </div>
        </div>
    </div>

    <!-- COMMON ERRORS -->
    <div class="card">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">❌ Các Lỗi Thường Gặp</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>🔴 Code 03 (Dữ liệu không đúng định dạng)</h6>
                    <ul>
                        <li>vnp_Amount có chứa dấu chấm (ví dụ: 10000.50 thay vì 1000050)</li>
                        <li>vnp_CreateDate có dấu gạch ngang (2026-03-17 thay vì 20260317)</li>
                        <li>vnp_OrderInfo quá dài hoặc chứa ký tự không hợp lệ</li>
                        <li>vnp_TxnRef bị repeat (trùng lặp trong ngày)</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6>🔴 Lỗi Hash Mismatch</h6>
                    <ul>
                        <li>Hashdata không URL-encode đúng</li>
                        <li>Dùng sha256 thay vì sha512</li>
                        <li>Hash Secret sai (kiểm tra case-sensitive)</li>
                        <li>Quên ksort() trước khi tính hash</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- QUICK FIX BUTTONS -->
    <div class="card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">🔧 Hành Động</h5>
        </div>
        <div class="card-body">
            <a href="javascript:history.back()" class="btn btn-secondary">← Quay lại</a>
            <a href="index.php?page=checkout" class="btn btn-primary">Về trang checkout</a>
            <button onclick="location.reload()" class="btn btn-warning">🔄 Reload logs</button>
        </div>
    </div>

</div>

</body>
</html>
