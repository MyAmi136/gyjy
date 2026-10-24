<?php

require_once "model/Order.php";
require_once "model/OrderItem.php";
require_once "model/Payment.php";
require_once "model/Product.php";
require_once "model/PayOS.php";

class OrderController{

    public function myOrders(){
        if(!isset($_SESSION['user']['user_id'])){
            header("Location:index.php?page=login&redirect=my_orders");
            exit;
        }

        $orderModel = new Order();
        $orders = $orderModel->getByCustomer($_SESSION['user']['user_id']);

        require "view/my_orders.php";
    }

    public function myOrderDetail(){
        if(!isset($_SESSION['user']['user_id'])){
            header("Location:index.php?page=login&redirect=my_orders");
            exit;
        }

        $orderId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        if($orderId === false || $orderId < 1){
            http_response_code(404);
            $order = false;
            $orderItems = [];
            require "view/my_order_detail.php";
            return;
        }

        $orderModel = new Order();
        $order = $orderModel->getCustomerOrder($orderId, $_SESSION['user']['user_id']);
        if(!$order){
            http_response_code(404);
            $orderItems = [];
            require "view/my_order_detail.php";
            return;
        }

        $orderItems = $orderModel->getCustomerOrderItems($orderId, $_SESSION['user']['user_id']);
        require "view/my_order_detail.php";
    }

    // =========================
    // Trang checkout
    // =========================
   public function checkout(){

    // ✅ CHƯA LOGIN → chuyển login
    if(!isset($_SESSION['user'])){
        header("Location:index.php?page=login&redirect=checkout");
        exit;
    }

    // giỏ hàng trống
    if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])){
        echo "Giỏ hàng trống";
        return;
    }

    require_once "view/checkout.php";
}


    // =========================
    // Xử lý đặt hàng
    // =========================
    public function placeOrder(){

     if($_SERVER['REQUEST_METHOD'] !== 'POST'){
         header("Location:index.php?page=checkout");
         exit;
     }

     // ✅ bắt buộc login
     if(!isset($_SESSION['user'])){
         header("Location:index.php?page=login");
        exit;
    }

    // giỏ hàng rỗng
    if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])){
        die("Giỏ hàng trống");
    }

    $orderModel = new Order();
    $orderItemModel = new OrderItem();
    $paymentModel = new Payment();
    $productModel = new Product();

    // ✅ lấy user thật từ session
    $user_id = $_SESSION['user']['user_id'];

    $address = trim($_POST['address'] ?? '');
    if($address === ''){
        $_SESSION['error'] = "Vui lòng nhập địa chỉ giao hàng.";
        header("Location:index.php?page=checkout");
        exit;
    }

    $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'cod';
    if(!in_array($payment_method, ['cod', 'mock', 'vnpay', 'payos'], true)){
        $_SESSION['error'] = "Phương thức thanh toán không hợp lệ.";
        header("Location:index.php?page=checkout");
        exit;
    }

    // Recalculate the order using current product prices and stock on the server.
    $total = 0;
    $pricesChanged = false;
    foreach($_SESSION['cart'] as $productId => $item){
        $productId = filter_var($productId, FILTER_VALIDATE_INT);
        $quantity = filter_var($item['qty'] ?? null, FILTER_VALIDATE_INT);
        $product = $productId !== false && $productId > 0
            ? $productModel->getById($productId)
            : false;

        if(!$product || (int) $product['status'] !== 1 || $quantity === false || $quantity < 1){
            $_SESSION['error'] = "Có sản phẩm trong giỏ hàng không còn khả dụng.";
            header("Location:index.php?page=cart");
            exit;
        }

        if((int) $product['stock'] < $quantity){
            $_SESSION['error'] = "Số lượng tồn kho không đủ cho sản phẩm: " . $product['product_name'];
            header("Location:index.php?page=cart");
            exit;
        }

        if((float) ($item['price'] ?? 0) !== (float) $product['price']){
            $pricesChanged = true;
        }
        $_SESSION['cart'][$productId]['price'] = (float) $product['price'];
        $_SESSION['cart'][$productId]['qty'] = $quantity;
        $total += (float) $product['price'] * $quantity;
    }

    if($pricesChanged){
        $_SESSION['error'] = "Giá một số sản phẩm đã thay đổi. Vui lòng kiểm tra lại đơn hàng.";
        header("Location:index.php?page=checkout");
        exit;
    }

    // tạo đơn hàng
    $order_id = $orderModel->createOrder(
        $user_id,
        $total,
        $address
    );

    foreach($_SESSION['cart'] as $id => $item){

        $orderItemModel->addItem(
            $order_id,
            $id,
            $item['qty'],
            $item['price']
        );
    }

    // ✅ Tạo payment record (pending)
    $paymentModel->create([
        'order_id' => $order_id,
        'payment_method' => $payment_method,
        'payment_status' => 'pending'
    ]);

    $_SESSION['last_order_id'] = $order_id;

    // =========================
    // MOCK PAYMENT (Demo Gateway)
    // =========================
    if($payment_method === "mock"){
        $_SESSION['mock_payment_order_id'] = $order_id;
        header("Location:index.php?page=mock_payment&order_id=" . $order_id . "&amount=" . $total);
        return;
    }

    // =========================
    // VNPAY
    // =========================
    if($payment_method === "vnpay"){
        $_SESSION['vnpay_order_id'] = $order_id;
        $this->vnpay($total, $order_id);
        return;
    }

    if($payment_method === "payos"){
        try {
            $payOS = new PayOS();
            $paymentLink = $payOS->createPaymentLink($order_id, $total);
            $_SESSION['payos_order_id'] = $order_id;
            header('Location: ' . $paymentLink['checkoutUrl']);
            exit;
        } catch (Throwable $exception) {
            error_log('PayOS payment link creation failed for order ' . $order_id . ': ' . $exception->getMessage());
            $error = 'Chưa thể tạo giao dịch PayOS. Đơn hàng của bạn vẫn được lưu; vui lòng kiểm tra cấu hình thanh toán hoặc liên hệ cửa hàng.';
            $retryPayOS = true;
            require "view/payment_error.php";
            return;
        }
    }

    // ✅ COD - Decrease stock immediately (assume reserved on order placement)
    if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
        $productModel = new Product();
        foreach($_SESSION['cart'] as $product_id => $item){
            $qty = $item['qty'] ?? 1;
            $success = $productModel->decreaseStock($product_id, $qty);
            if($success){
                error_log("Stock decreased (COD) - Product ID: $product_id, Qty: $qty");
            } else {
                error_log("Stock decrease FAILED (COD) - Product ID: $product_id, Qty: $qty");
            }
        }
    }

    // ✅ Clear cart and redirect
    unset($_SESSION['cart']);

    header("Location:index.php?page=order-success");
}


    // =========================
    // Tạo link thanh toán VNPAY
    // =========================
    private function vnpay($amount, $order_id){

        require_once "config/vnpay.php";

        $now = new DateTimeImmutable('now', new DateTimeZone('Asia/Ho_Chi_Minh'));
        $vnp_TxnRef = "ORD" . $order_id . $now->format('YmdHis');
        $vnp_OrderInfo = "Thanh toan don hang #" . $order_id;
        $vnp_OrderType = "billpayment";
        $vnp_Amount = (int) round($amount * 100);
        $vnp_Locale = "vn";
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => $now->format('YmdHis'),
            "vnp_ExpireDate" => $now->modify('+15 minutes')->format('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        );

        ksort($inputData);

        $query = "";
        $hashdata = "";

        // ✅ FIX: URL-encode both hashdata and query string consistently
        foreach ($inputData as $key => $value) {
            $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            $query .= urlencode($key)."=".urlencode($value).'&';
        }

        $vnpSecureHash = hash_hmac('sha512', ltrim($hashdata,'&'), $vnp_HashSecret);
        $vnp_Url = $vnp_Url . "?" . $query . 'vnp_SecureHash=' . $vnpSecureHash;

        error_log("VNPAY payment link created for order " . $order_id . " with reference " . $vnp_TxnRef);

        header('Location: '.$vnp_Url);
        exit;
    }


    // =========================
    // VNPAY Return
    // =========================
    public function vnpayReturn(){

        require_once "config/vnpay.php";

        $error = "Phản hồi thanh toán không hợp lệ. Vui lòng thử lại.";
        $order_id = null;
        $vnp_SecureHash = $_GET['vnp_SecureHash'] ?? '';

        if(!is_string($vnp_SecureHash) || $vnp_SecureHash === ''){
            error_log("VNPAY return rejected: missing secure hash");
            require "view/payment_error.php";
            return;
        }

        $vnp_Params = $_GET;
        unset($vnp_Params['vnp_SecureHash'], $vnp_Params['vnp_SecureHashType']);
        foreach($vnp_Params as $value){
            if(!is_scalar($value)){
                error_log("VNPAY return rejected: non-scalar parameter");
                require "view/payment_error.php";
                return;
            }
        }

        ksort($vnp_Params);
        $hashdata = "";
        foreach($vnp_Params as $key => $value){
            $hashdata .= '&' . urlencode($key) . "=" . urlencode((string) $value);
        }
        $vnp_SecureHashCheck = hash_hmac('sha512', ltrim($hashdata, '&'), $vnp_HashSecret);

        if(!hash_equals($vnp_SecureHashCheck, $vnp_SecureHash)){
            error_log("VNPAY return rejected: secure hash mismatch");
            $error = "Không thể xác minh giao dịch VNPAY. Vui lòng liên hệ cửa hàng để được hỗ trợ.";
            require "view/payment_error.php";
            return;
        }

        if(($vnp_Params['vnp_TmnCode'] ?? '') !== $vnp_TmnCode){
            error_log("VNPAY return rejected: merchant code mismatch");
            require "view/payment_error.php";
            return;
        }

        $txnRef = (string) ($vnp_Params['vnp_TxnRef'] ?? '');
        if(!preg_match('/^ORD([1-9][0-9]*)[0-9]{14}$/D', $txnRef, $matches)){
            error_log("VNPAY return rejected: invalid transaction reference");
            require "view/payment_error.php";
            return;
        }

        $orderId = filter_var($matches[1], FILTER_VALIDATE_INT);
        if($orderId === false || $orderId < 1){
            error_log("VNPAY return rejected: invalid order identifier");
            require "view/payment_error.php";
            return;
        }
        $order_id = $orderId;

        $paymentModel = new Payment();
        $payment = $paymentModel->getByOrderId($orderId);
        $vnpAmount = filter_var($vnp_Params['vnp_Amount'] ?? null, FILTER_VALIDATE_INT);
        $expectedAmount = $payment
            ? (int) round((float) $payment['total_amount'] * 100)
            : null;

        if(
            !$payment ||
            $payment['payment_method'] !== 'vnpay' ||
            $vnpAmount === false ||
            $vnpAmount !== $expectedAmount
        ){
            error_log("VNPAY return rejected: order or amount mismatch for reference " . $txnRef);
            $error = "Thông tin giao dịch không khớp với đơn hàng. Vui lòng liên hệ cửa hàng.";
            require "view/payment_error.php";
            return;
        }

        $responseCode = (string) ($vnp_Params['vnp_ResponseCode'] ?? '');
        $transactionStatus = (string) ($vnp_Params['vnp_TransactionStatus'] ?? '');

        if($responseCode === "00" && $transactionStatus === "00"){
            if($payment['payment_status'] !== 'paid'){
                $transactionCode = (string) ($vnp_Params['vnp_TransactionNo'] ?? $txnRef);
                $paymentModel->updateStatus($payment['payment_id'], 'paid', $transactionCode);

                $orderModel = new Order();
                $productModel = new Product();
                foreach($orderModel->getOrderItems($orderId) as $item){
                    $success = $productModel->decreaseStock($item['product_id'], $item['quantity']);
                    if(!$success){
                        error_log("VNPAY payment succeeded but stock update failed for order " . $orderId . ", product " . $item['product_id']);
                    }
                }
            }

            $_SESSION['last_order_id'] = $orderId;
            if((int) ($_SESSION['vnpay_order_id'] ?? 0) === $orderId){
                unset($_SESSION['cart'], $_SESSION['vnpay_order_id']);
            }
            header("Location:index.php?page=order-success");
            exit;
        }

        if($payment['payment_status'] !== 'paid'){
            $paymentModel->updateStatus(
                $payment['payment_id'],
                'failed',
                'VNPAY_CODE_' . ($responseCode !== '' ? $responseCode : 'UNKNOWN')
            );
        }

        $error = "Thanh toán chưa thành công hoặc giao dịch đã hết thời gian (mã: "
            . htmlspecialchars($responseCode !== '' ? $responseCode : 'không xác định', ENT_QUOTES, 'UTF-8')
            . "). Hãy thử đặt hàng và thanh toán lại.";
        error_log("VNPAY payment failed for order " . $orderId . " with response code " . $responseCode);
        require "view/payment_error.php";
    }

    public function payosReturn()
    {
        $orderId = filter_var($_GET['order_id'] ?? null, FILTER_VALIDATE_INT);
        if (
            $orderId === false ||
            $orderId < 1 ||
            (int) ($_SESSION['payos_order_id'] ?? 0) !== $orderId
        ) {
            http_response_code(400);
            $error = 'Không tìm thấy đơn hàng thanh toán.';
            require "view/payment_error.php";
            return;
        }

        $paymentModel = new Payment();
        $payment = $paymentModel->getByOrderId($orderId);
        if (!$payment || $payment['payment_method'] !== 'payos') {
            http_response_code(404);
            $error = 'Không tìm thấy thông tin thanh toán PayOS.';
            require "view/payment_error.php";
            return;
        }

        if ($payment['payment_status'] === 'paid') {
            unset($_SESSION['cart'], $_SESSION['payos_order_id']);
        }

        $_SESSION['last_order_id'] = $orderId;
        require "view/payos_return.php";
    }

    public function retryPayOS()
    {
        if (!isset($_SESSION['user']['user_id'])) {
            header("Location:index.php?page=login&redirect=my_orders");
            exit;
        }

        $orderId = filter_var($_GET['order_id'] ?? null, FILTER_VALIDATE_INT);
        if ($orderId === false || $orderId < 1) {
            http_response_code(404);
            echo "Không tìm thấy đơn hàng.";
            return;
        }

        $order = (new Order())->getCustomerOrder($orderId, $_SESSION['user']['user_id']);
        if (
            !$order ||
            $order['payment_method'] !== 'payos' ||
            $order['payment_status'] === 'paid' ||
            $order['status'] === 'cancelled'
        ) {
            http_response_code(404);
            echo "Đơn hàng không thể thanh toán lại.";
            return;
        }

        try {
            $paymentLink = (new PayOS())->createPaymentLink($orderId, $order['total_amount']);
            $_SESSION['payos_order_id'] = $orderId;
            header('Location: ' . $paymentLink['checkoutUrl']);
            exit;
        } catch (Throwable $exception) {
            error_log('PayOS retry failed for order ' . $orderId . ': ' . $exception->getMessage());
            $error = 'Chưa thể tạo giao dịch PayOS. Vui lòng thử lại sau.';
            $retryPayOS = true;
            $order_id = $orderId;
            require "view/payment_error.php";
        }
    }

    // =========================
    // MOCK PAYMENT - Show payment form
    // =========================
    public function mockPayment(){
        require "view/mock_payment.php";
    }

    // =========================
    // MOCK PAYMENT - Process payment
    // =========================
    public function mockPaymentProcess(){

        require_once "config/mock_payment.php";

        $order_id = $_POST['order_id'] ?? null;
        $amount = $_POST['amount'] ?? null;
        $card_number = $_POST['card_number'] ?? null;
        $cardholder_name = $_POST['cardholder_name'] ?? null;
        $cvv = $_POST['cvv'] ?? null;

        // Validate inputs
        if(!$order_id || !$amount){
            $_SESSION['error'] = "Dữ liệu thanh toán không đầy đủ";
            header("Location:index.php?page=mock_payment&order_id=" . $order_id . "&amount=" . $amount);
            exit;
        }

        // Process mock payment
        $result = processMockPayment($amount, $order_id);

        // Log payment attempt
        error_log("Mock Payment Attempt - Order: $order_id, Amount: $amount, Card: " . substr($card_number, -4) . ", Result: " . $result['status']);

        if($result['status'] === 'success'){
            
            // ✅ Update payment status to PAID
            $paymentModel = new Payment();
            $payment = $paymentModel->getByOrderId($order_id);
            
            if($payment){
                $paymentModel->updateStatus(
                    $payment['payment_id'], 
                    'paid', 
                    $result['transaction_id']
                );
                
                error_log("Payment marked PAID - Payment ID: " . $payment['payment_id'] . ", Transaction: " . $result['transaction_id']);
            }

            // ✅ Decrease stock for all items in order
            if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
                $productModel = new Product();
                foreach($_SESSION['cart'] as $product_id => $item){
                    $qty = $item['qty'] ?? 1;
                    $success = $productModel->decreaseStock($product_id, $qty);
                    if($success){
                        error_log("Stock decreased - Product ID: $product_id, Qty: $qty");
                    } else {
                        error_log("Stock decrease FAILED - Product ID: $product_id, Qty: $qty");
                    }
                }
            }

            // ✅ Clear cart on success
            if(isset($_SESSION['cart'])){
                unset($_SESSION['cart']);
            }

            $_SESSION['payment_result'] = $result;
            $_SESSION['success'] = "Thanh toán thành công!";
            
            header("Location:index.php?page=order-success");
            exit;

        } else {

            // ❌ Payment failed - Update payment status to FAILED
            $paymentModel = new Payment();
            $payment = $paymentModel->getByOrderId($order_id);
            
            if($payment){
                $paymentModel->updateStatus(
                    $payment['payment_id'], 
                    'failed', 
                    $result['code']
                );
            }

            // ❌ Payment failed
            $error = "Thanh toán thất bại: " . $result['message'];
            $_SESSION['error'] = $error;
            
            error_log("Mock Payment Failed - Order: $order_id, Code: " . $result['code']);
            
            require "view/payment_error.php";
            exit;

        }

    }

}