<?php require "view/layout/header.php"; ?>

<div class="container mt-5">

<h2 class="mb-4">
  <i class="bi bi-credit-card"></i> Thanh toán trực tuyến (Demo)
</h2>

<div class="row">

<div class="col-md-6">

<div class="card shadow">

<div class="card-body">

<h5 class="card-title mb-4">💳 Thông tin thẻ</h5>

<form id="payment-form" method="POST" action="index.php?page=mock_payment_process">

<input type="hidden" name="order_id" value="<?= $_GET['order_id'] ?? '' ?>">
<input type="hidden" name="amount" value="<?= $_GET['amount'] ?? '' ?>">

<!-- Card Number -->
<div class="mb-3">
<label class="form-label fw-bold">Số thẻ</label>
<select class="form-select" id="card_number" name="card_number" required onchange="updateCardInfo()">
<option value="">-- Chọn thẻ test --</option>
<option value="4111111111111111">VISA: 4111111111111111</option>
<option value="5555555555554444">MasterCard: 5555555555554444</option>
<option value="378282246310005">AmEx: 378282246310005</option>
</select>
<small class="text-muted">Chọn một trong các thẻ test trên</small>
</div>

<!-- Cardholder Name -->
<div class="mb-3">
<label class="form-label fw-bold">Tên chủ thẻ</label>
<input type="text" class="form-control" name="cardholder_name" value="<?= $_SESSION['user']['full_name'] ?? 'Nguyen Van A' ?>" required>
</div>

<!-- Expiry -->
<div class="row">
<div class="col-md-6 mb-3">
<label class="form-label fw-bold">Hạn sử dụng</label>
<input type="text" class="form-control" placeholder="MM/YY" value="07/25" disabled>
<small class="text-muted">07/25</small>
</div>

<div class="col-md-6 mb-3">
<label class="form-label fw-bold">CVV</label>
<input type="text" class="form-control" name="cvv" placeholder="CVV" maxlength="4" required>
<small class="text-muted">Ví dụ: 123</small>
</div>
</div>

<button type="submit" class="btn btn-danger btn-lg w-100">
<i class="bi bi-check-circle"></i> Thanh toán
</button>

<a href="index.php?page=checkout" class="btn btn-secondary w-100 mt-2">
<i class="bi bi-arrow-left"></i> Quay lại
</a>

</form>

</div>

</div>

</div>

<!-- RIGHT: Payment Info -->
<div class="col-md-6">

<div class="card shadow bg-light">

<div class="card-body">

<h5 class="card-title mb-4">ℹ️ Thông tin thanh toán</h5>

<div class="mb-3">
<strong>Đơn hàng:</strong> #<?= $_GET['order_id'] ?? 'N/A' ?>
</div>

<div class="mb-3">
<strong>Số tiền:</strong> 
<span class="text-danger fs-5 fw-bold"><?= isset($_GET['amount']) ? number_format($_GET['amount']) . 'đ' : 'N/A' ?></span>
</div>

<hr>

<h6 class="fw-bold mb-3">📌 Thẻ test có sẵn:</h6>

<div class="mb-2">
<strong>VISA</strong><br>
<small>4111111111111111 | 07/25 | 123</small>
</div>

<div class="mb-2">
<strong>MasterCard</strong><br>
<small>5555555555554444 | 07/25 | 123</small>
</div>

<div class="mb-2">
<strong>AmEx</strong><br>
<small>378282246310005 | 07/25 | 1234</small>
</div>

<hr>

<p class="text-muted small">
<i class="bi bi-info-circle"></i> 
<strong>Demo Mode:</strong> Thanh toán này là giả lập cho mục đích học tập. 
Không tiêu tiền thật. Trong production, dùng VNPAY/PayOS thực.
</p>

</div>

</div>

</div>

</div>

</div>

<script>
function updateCardInfo(){
    const select = document.getElementById('card_number');
    const card = select.value;
    // Card info updated in select, you can show more details here if needed
}
</script>

<?php require "view/layout/footer.php"; ?>
