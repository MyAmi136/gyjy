<?php require "view/layout/header.php"; ?>

<h2 class="mb-4">
  <i class="bi bi-tag"></i> Sản phẩm theo danh mục
</h2>

<?php if(empty($products)): ?>

<div class="alert alert-info">
<i class="bi bi-info-circle"></i> Danh mục này chưa có sản phẩm
</div>

<?php else: ?>

<div class="row g-4">

<?php foreach($products as $product){ ?>

<div class="col-6 col-md-4 col-lg-3">

<?php
$cardProduct = $product;
$compactProductCard = false;
$showProductDescription = false;
$productCardBadge = null;
require "view/layout/product_card.php";
?>

</div>

<?php } ?>

</div>

<?php endif; ?>

<div class="mt-4 d-flex gap-2 flex-wrap">
<a href="index.php" class="btn btn-secondary">
<i class="bi bi-arrow-left"></i> Quay lại trang chủ
</a>
<a href="index.php?page=products" class="btn btn-outline-danger">
<i class="bi bi-grid me-1"></i> Xem tất cả sản phẩm
</a>
</div>

<?php require "view/layout/footer.php"; ?>
