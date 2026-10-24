<?php require "view/layout/header.php"; ?>



<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
    <h2 class="mb-0">
        <i class="bi bi-box"></i> Quản lý sản phẩm
    </h2>
    <div class="d-flex gap-2 flex-wrap">
        <button type="button" class="btn btn-secondary" onclick="window.history.length > 1 ? window.history.back() : window.location.href='index.php?page=admin_dashboard';">
            <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Quay lại trang trước
        </button>
        <a class="btn btn-outline-secondary" href="index.php?page=admin_dashboard">
            <i class="bi bi-speedometer2 me-1" aria-hidden="true"></i>Về trang quản trị
        </a>
    </div>
</div>



<?php if (isset($_SESSION['success']) || isset($_SESSION['error'])): ?>

<div class="alert alert-<?= isset($_SESSION['error']) ? 'danger' : 'success' ?> alert-dismissible fade show" role="alert">

<?= htmlspecialchars($_SESSION['error'] ?? $_SESSION['success'], ENT_QUOTES, 'UTF-8') ?>

<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>

</div>

<?php unset($_SESSION['success'], $_SESSION['error']); ?>

<?php endif; ?>



<?php

if(isset($_GET['msg']) && $_GET['msg']=="deleted"){

?>

<div class="alert alert-success">

<i class="bi bi-check-circle"></i>

Xóa sản phẩm thành công

</div>

<?php } ?>



<div class="mb-3">



<a href="index.php?page=admin_create_product"

class="btn btn-primary">



<i class="bi bi-plus-circle"></i>

Thêm sản phẩm



</a>



</div>



<div class="card shadow">



<div class="card-body">



<table class="table table-hover align-middle">



<thead class="table-dark">



<tr>

<th>ID</th>

<th>Ảnh</th>

<th>Tên sản phẩm</th>

<th>Giá</th>

<th>Action</th>

</tr>



</thead>



<tbody>



<?php foreach($products as $p){ ?>



<tr>



<td>#<?= $p['product_id'] ?></td>



<td>

<img src="<?= htmlspecialchars($p['featured_image_url'] ?: 'img/product-placeholder.svg', ENT_QUOTES, 'UTF-8') ?>"

     width="60" height="60" class="rounded" alt=""

     style="object-fit: cover;"

     onerror="this.onerror=null;this.src='img/product-placeholder.svg';">

</td>



<td class="fw-bold">

<?= $p['product_name'] ?>

</td>



<td class="text-danger fw-bold">

<?= number_format($p['price']) ?> đ

</td>



<td>



<a href="index.php?page=admin_edit_product&id=<?= $p['product_id'] ?>"

class="btn btn-warning btn-sm">



<i class="bi bi-pencil"></i>

Sửa



</a>



<a href="index.php?page=admin_delete_product&id=<?= $p['product_id'] ?>"

class="btn btn-danger btn-sm"

onclick="return confirm('Bạn có chắc muốn xóa?')">



<i class="bi bi-trash"></i>

Xóa



</a>



</td>



</tr>



<?php } ?>



</tbody>



</table>



</div>

</div>



<?php require "view/layout/footer.php"; ?>