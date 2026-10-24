<?php require "view/layout/header.php"; ?>

<div class="row justify-content-center">

<div class="col-md-6">

<h2 class="mb-4">
<i class="bi bi-pencil"></i> Sửa sản phẩm
</h2>

<div class="card shadow">

<div class="card-body">

<?php if (isset($_SESSION['error'])): ?>
<div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?></div>
<?php unset($_SESSION['error']); ?>
<?php endif; ?>

<form method="POST" action="index.php?page=admin_update_product" enctype="multipart/form-data">

<input type="hidden" name="product_id" value="<?= $data['product_id'] ?>">

<div class="mb-3">
<label class="form-label fw-bold">Tên sản phẩm</label>
<input type="text" name="product_name" value="<?= $data['product_name'] ?>" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label fw-bold">Giá (VND)</label>
<input type="number" step="0.01" name="price" value="<?= $data['price'] ?>" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label fw-bold">Danh mục</label>
<select name="category_id" class="form-select">
    <option value="">-- Chọn danh mục --</option>
    <?php
    require_once "model/Category.php";
    $catModel = new Category();
    $categories = $catModel->getAll();
    foreach($categories as $cat){
        $selected = ($cat['category_id'] == $data['category_id']) ? 'selected' : '';
        echo '<option value="'.$cat['category_id'].'" '.$selected.'>'.$cat['category_name'].'</option>';
    }
    ?>
</select>
</div>

<div class="mb-3">
<label class="form-label fw-bold">Mô tả</label>
<textarea name="description" class="form-control" rows="4"><?= $data['description'] ?></textarea>
</div>

<div class="mb-3">
<label for="featured-image" class="form-label fw-bold">Ảnh sản phẩm</label>
<?php if (!empty($data['featured_image_url'])): ?>
<div class="mb-2">
    <img src="<?= htmlspecialchars($data['featured_image_url'], ENT_QUOTES, 'UTF-8') ?>"
         class="img-thumbnail" alt="Ảnh sản phẩm hiện tại" style="max-height: 180px;"
         onerror="this.onerror=null;this.src='img/product-placeholder.svg';">
</div>
<?php endif; ?>
<input id="featured-image" type="file" name="featured_image" class="form-control"
       accept="image/jpeg,image/png,image/gif,image/webp,image/bmp,image/avif">
<div class="form-text">Chọn ảnh mới nếu muốn thay ảnh hiện tại. JPG, PNG, GIF, WEBP, BMP hoặc AVIF, tối đa 5 MB.</div>
<img id="image-preview" class="img-thumbnail mt-3 d-none" alt="Xem trước ảnh mới"
     style="max-height: 220px;">
</div>

<button type="submit" class="btn btn-warning w-100">
<i class="bi bi-check-circle"></i> Cập nhật
</button>

<a href="index.php?page=admin_products" class="btn btn-secondary w-100 mt-2">
<i class="bi bi-arrow-left"></i> Quay lại
</a>

</form>

</div>

</div>

</div>
</div>

<script>
document.getElementById('featured-image').addEventListener('change', function () {
    const preview = document.getElementById('image-preview');
    const file = this.files[0];
    if (!file) {
        preview.removeAttribute('src');
        preview.classList.add('d-none');
        return;
    }
    preview.src = URL.createObjectURL(file);
    preview.classList.remove('d-none');
});
</script>

<?php require "view/layout/footer.php"; ?>
