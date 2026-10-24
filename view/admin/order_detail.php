<?php require "view/layout/header.php"; ?>

<div class="container">

<h2 class="mb-4">
<i class="bi bi-box-seam"></i> Chi tiết đơn hàng #<?= $_GET['id'] ?>
</h2>

<div class="card shadow">

<div class="card-body">

<table class="table table-bordered align-middle">

<tr class="table-dark">
<th>Sản phẩm</th>
<th>Số lượng</th>
<th>Giá</th>
<th>Tổng</th>
</tr>

<?php 
$total = 0;
foreach($orderItems as $item): 
$subtotal = $item['quantity'] * $item['price'];
$total += $subtotal;
?>

<tr>

<td><?= $item['product_name'] ?></td>

<td class="text-center"><?= $item['quantity'] ?></td>

<td><?= number_format($item['price']) ?> đ</td>

<td class="text-danger fw-bold">
<?= number_format($subtotal) ?> đ
</td>

</tr>

<?php endforeach; ?>

<tr class="table-warning fw-bold">
<td colspan="3" class="text-end">TỔNG CỘNG:</td>
<td><?= number_format($total) ?> đ</td>
</tr>

</table>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-body">

<h5>Cập nhật trạng thái đơn hàng</h5>

<form method="POST" action="index.php?page=admin_update_status">

<input type="hidden" name="order_id" value="<?= $_GET['id'] ?>">

<div class="row align-items-center">

<div class="col-md-6">
<select name="status" class="form-select">
<option value="pending">Chờ xử lý</option>
<option value="processing">Đang xử lý</option>
<option value="shipping">Đang giao</option>
<option value="completed">Hoàn thành</option>
<option value="cancelled">Hủy</option>
</select>
</div>

<div class="col-md-6">
<button class="btn btn-success w-100">
<i class="bi bi-check-circle"></i> Cập nhật
</button>
</div>

</div>

</form>

</div>

</div>

<div class="mt-4 d-flex gap-2 flex-wrap">
<button type="button" class="btn btn-secondary" onclick="window.history.length > 1 ? window.history.back() : window.location.href='index.php?page=admin_orders';">
<i class="bi bi-arrow-left"></i> Quay lại trang trước
</button>
<a href="index.php?page=admin_orders" class="btn btn-outline-secondary">
<i class="bi bi-receipt"></i> Danh sách đơn
</a>
<a href="index.php?page=admin_dashboard" class="btn btn-outline-secondary">
<i class="bi bi-speedometer2"></i> Về trang quản trị
</a>
</div>

</div>

<?php require "view/layout/footer.php"; ?>
