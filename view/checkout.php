<?php require "view/layout/header.php"; ?>

<div class="container mt-4">

<?php if (isset($_SESSION['error'])): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
</div>
<?php unset($_SESSION['error']); ?>
<?php endif; ?>

<h2 class="mb-4">
  <i class="bi bi-credit-card"></i> Thanh toán đơn hàng
</h2>

<div class="row">

<!-- LEFT: ORDER SUMMARY -->
<div class="col-md-7">

<div class="card shadow mb-4">

<div class="card-body">

<h5 class="card-title mb-3">
  <i class="bi bi-box"></i> Sản phẩm trong đơn hàng
</h5>

<table class="table table-sm align-middle">

<tr class="table-light">
<th>Sản phẩm</th>
<th>SL</th>
<th>Giá</th>
<th>Tổng</th>
</tr>

<?php
$total=0;

foreach($_SESSION['cart'] as $item):

$subtotal=$item['price']*$item['qty'];
$total+=$subtotal;
?>

<tr>
<td><?= $item['name'] ?></td>
<td class="text-center"><?= $item['qty'] ?></td>
<td><?= number_format($item['price']) ?>đ</td>
<td class="fw-bold text-danger"><?= number_format($subtotal) ?>đ</td>
</tr>

<?php endforeach; ?>

<tr class="table-warning fw-bold">
<td colspan="3" class="text-end">TỔNG CỘNG:</td>
<td><?= number_format($total) ?> đ</td>
</tr>

</table>

</div>

</div>

</div>

<!-- RIGHT: CHECKOUT FORM -->
<div class="col-md-5">

<div class="card shadow">

<div class="card-body">

<h5 class="card-title mb-4">
  <i class="bi bi-person"></i> Thông tin giao hàng
</h5>

<form method="POST" action="index.php?page=place-order">

<input type="hidden" name="total" value="<?= $total ?>">

<div class="mb-3">
<label class="form-label fw-bold">Họ tên</label>
<input class="form-control" name="name" 
       value="<?= $_SESSION['user']['full_name'] ?? '' ?>"
       placeholder="Nhập tên đầy đủ"
       required>
</div>

<div class="mb-3">
<label class="form-label fw-bold">Số điện thoại</label>
<input class="form-control" type="tel" name="phone" 
       placeholder="0900000000"
       required>
</div>

<div class="mb-3">
<label class="form-label fw-bold">Địa chỉ giao hàng</label>
<textarea class="form-control" name="address" rows="3"
          placeholder="Ví dụ: 123 Đường A, Phường B, Quận C, TP HCM"
          required></textarea>
</div>

<hr>

<h6 class="fw-bold mb-3">
  <i class="bi bi-wallet2"></i> Phương thức thanh toán
</h6>

<div class="form-check mb-3">
<input class="form-check-input" type="radio"
       name="payment_method"
       value="cod"
       id="payment_cod"
       checked>
<label class="form-check-label" for="payment_cod">
  <strong>Thanh toán khi nhận hàng (COD)</strong>
  <div class="text-muted small">Bạn sẽ thanh toán khi nhân viên giao hàng</div>
</label>
</div>

<div class="form-check mb-3">
<input class="form-check-input" type="radio"
       name="payment_method"
       value="mock"
       id="payment_mock">
<label class="form-check-label" for="payment_mock">
  <strong>Thanh toán trực tuyến</strong>
  <div class="text-muted small">Thẻ test cho mục đích demo - Không tiêu tiền thật</div>
</label>
</div>

<div class="form-check mb-4">
<input class="form-check-input" type="radio"
       name="payment_method"
       value="vnpay"
       id="payment_vnpay">
<label class="form-check-label" for="payment_vnpay">
  <strong>Thanh toán trực tuyến (VNPAY)</strong>
  <div class="text-muted small">Thanh toán an toàn bằng thẻ/ví điện tử</div>
</label>
</div>

<div class="form-check mb-4">
<input class="form-check-input" type="radio"
       name="payment_method"
       value="payos"
       id="payment_payos">
<label class="form-check-label" for="payment_payos">
  <strong>Chuyển khoản ngân hàng bằng mã QR</strong>
  <div class="text-muted small">Quét mã QR PayOS; đơn hàng tự xác nhận khi ngân hàng báo giao dịch thành công</div>
</label>
</div>

<button class="btn btn-danger btn-lg w-100 py-2">
<i class="bi bi-check-circle"></i> Đặt hàng ngay
</button>

<a href="index.php?page=cart" class="btn btn-secondary w-100 mt-2">
<i class="bi bi-arrow-left"></i> Quay lại giỏ hàng
</a>

</form>

</div>

</div>

</div>

</div>

</div>

<?php require "view/layout/footer.php"; ?>
