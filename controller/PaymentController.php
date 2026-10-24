<?php
class PaymentController{

public function vnpay(){

$order_id = $_GET['order_id'];

$total = $_SESSION['last_total'];

$vnp_TmnCode = "DEMO";
$vnp_HashSecret = "SECRET";

$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";

$vnp_Returnurl = "http://localhost/PTWeb/cosmetics-shop/index.php?page=vnpay_return";

$vnp_TxnRef = $order_id;

$vnp_Amount = $total * 100;

$vnp_OrderInfo = "Thanh toan don hang";

$vnp_Locale = "vn";

$vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

}
}
?>