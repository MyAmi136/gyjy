<?php require "view/layout/header.php"; ?>

<h2 class="mb-4">
<i class="bi bi-speedometer2"></i> Admin Dashboard
</h2>

<div class="row g-4">

<!-- TOTAL ORDERS -->
<div class="col-md-4">
<div class="card shadow border-0">
<div class="card-body text-center">

<i class="bi bi-cart-check fs-1 text-primary"></i>

<h5 class="mt-2">Tổng đơn hàng</h5>

<h2 class="fw-bold">
<?= $stats['total_orders'] ?? 0 ?>
</h2>

</div>
</div>
</div>

<!-- REVENUE -->
<div class="col-md-4">
<div class="card shadow border-0">
<div class="card-body text-center">

<i class="bi bi-cash-stack fs-1 text-success"></i>

<h5 class="mt-2">Doanh thu</h5>

<h2 class="fw-bold text-success">
<?= number_format($stats['revenue'] ?? 0) ?> đ
</h2>

</div>
</div>
</div>

</div>

<hr class="my-5">

<h4 class="mb-4">Quản lý chính</h4>

<div class="d-flex gap-3 flex-wrap">

<a href="index.php?page=admin_products"
class="btn btn-primary btn-lg">
<i class="bi bi-box"></i>
Quản lý sản phẩm
</a>

<a href="index.php?page=admin_orders"
class="btn btn-success btn-lg">
<i class="bi bi-receipt"></i>
Quản lý đơn hàng
</a>

<a href="index.php?page=admin_posts"
class="btn btn-danger btn-lg">
<i class="bi bi-journal-text"></i>
Quản lý bài viết
</a>

<a href="index.php?page=admin_statistics"
class="btn btn-info btn-lg">
<i class="bi bi-graph-up"></i>
Thống kê
</a>

<a href="index.php"
class="btn btn-outline-dark btn-lg">
<i class="bi bi-shop"></i>
Xem trang chủ Shop
</a>

</div>

<?php require "view/layout/footer.php"; ?>
