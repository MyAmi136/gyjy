<?php require "view/layout/header.php"; ?>

<div class="container mt-4">

<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
    <h2 class="mb-0">
        <i class="bi bi-cart"></i> Giỏ hàng của bạn
    </h2>
    <a href="index.php?page=clear-cart" class="btn btn-outline-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng?');">
        <i class="bi bi-trash"></i> Làm trống giỏ hàng
    </a>
</div>

<?php
// Display success/error messages
if(isset($_SESSION['success'])){
    echo '<div class="alert alert-success alert-dismissible fade show">
    <i class="bi bi-check-circle"></i> ' . htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') . '
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>';
    unset($_SESSION['success']);
}

if(isset($_SESSION['error'])){
    echo '<div class="alert alert-danger alert-dismissible fade show">
    <i class="bi bi-exclamation-circle"></i> ' . htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') . '
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>';
    unset($_SESSION['error']);
}
?>

<?php
if(!isset($_SESSION['cart']) || count($_SESSION['cart'])==0){
echo '<div class="alert alert-info"><i class="bi bi-info-circle"></i> Giỏ hàng trống. <a href="index.php">Tiếp tục mua sắm</a></div>';
require "view/layout/footer.php";
return;
}
?>

<div class="card shadow">

<div class="card-body">

<table class="table table-hover align-middle mb-0">

<thead class="table-light">
<tr>
<th>Sản phẩm</th>
<th>Giá</th>
<th style="width: 160px;">Số lượng</th>
<th>Tổng</th>
<th style="width: 100px;"></th>
</tr>
</thead>

<tbody>

<?php
$total = 0;

foreach($_SESSION['cart'] as $id=>$item):

$subtotal = $item['price'] * $item['qty'];
$total += $subtotal;
?>

<tr>

<td>

<div class="d-flex align-items-center gap-3">

<?php if(!empty($item['image_url']) && file_exists("img/" . $item['image_url'])){ ?>
<img src="img/<?= $item['image_url'] ?>" width="50" class="rounded">
<?php } else { ?>
<img src="https://via.placeholder.com/50" width="50" class="rounded">
<?php } ?>

<strong><?= $item['name'] ?></strong>

</div>

</td>

<td>
<?= number_format($item['price']) ?> đ
</td>

<td>

<div class="btn-group" role="group">

<a class="btn btn-sm btn-outline-secondary"
href="index.php?page=decrease-cart&id=<?= $id ?>">
−
</a>

<button type="button" class="btn btn-sm btn-light" disabled>
<?= $item['qty'] ?>
</button>

<a class="btn btn-sm btn-outline-secondary"
href="index.php?page=increase-cart&id=<?= $id ?>">
+
</a>

</div>

</td>

<td class="fw-bold text-danger">
<?= number_format($subtotal) ?> đ
</td>

<td>

<a class="btn btn-danger btn-sm"
href="index.php?page=remove-cart&id=<?= $id ?>">
<i class="bi bi-trash"></i>
</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

<!-- ===== SUMMARY ===== -->
<div class="row mt-4">

<div class="col-md-8"></div>

<div class="col-md-4">

<div class="card border">

<div class="card-body">

<h5 class="card-title">Tóm tắt đơn hàng</h5>

<hr>

<div class="d-flex justify-content-between mb-2">
<span>Tạm tính:</span>
<strong><?= number_format($total) ?> đ</strong>
</div>

<div class="d-flex justify-content-between mb-3">
<span>Phí vận chuyển:</span>
<strong class="text-success">Miễn phí</strong>
</div>

<hr>

<div class="d-flex justify-content-between mb-4">
<h5>Tổng tiền:</h5>
<h5 class="text-danger fw-bold"><?= number_format($total) ?> đ</h5>
</div>

<?php if(isset($_SESSION['user'])){ ?>

<a href="index.php?page=checkout" class="btn btn-danger w-100 btn-lg">
<i class="bi bi-credit-card"></i> Thanh toán
</a>

<?php } else { ?>

<a href="index.php?page=login" class="btn btn-warning w-100 btn-lg">
<i class="bi bi-lock"></i> Đăng nhập để đặt hàng
</a>

<?php } ?>

<a href="index.php" class="btn btn-secondary w-100 mt-2">
<i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
</a>

</div>

</div>

</div>

</div>

<?php require "view/layout/footer.php"; ?>
