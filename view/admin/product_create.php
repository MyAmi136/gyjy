<?php require "view/layout/header.php"; ?>

<div class="row justify-content-center">

<div class="col-md-6">

<h2 class="mb-4">
<i class="bi bi-plus-circle"></i> Thêm sản phẩm
</h2>

<div class="card shadow">

<div class="card-body">

<?php if (isset($_SESSION['error'])): ?>
<div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?></div>
<?php unset($_SESSION['error']); ?>
<?php endif; ?>

<form method="POST" action="index.php?page=admin_save_product" enctype="multipart/form-data">

<div class="mb-3">
<label class="form-label fw-bold">Tên sản phẩm</label>
<input type="text" name="product_name" placeholder="Tên sản phẩm" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label fw-bold">Giá (VND)</label>
<input type="number" step="0.01" name="price" placeholder="Giá" class="form-control" required>
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
        echo '<option value="'.$cat['category_id'].'">'.$cat['category_name'].'</option>';
    }
    ?>
</select>
</div>

<div class="mb-3">
<label class="form-label fw-bold">Mô tả</label>
<textarea name="description" class="form-control" rows="4"></textarea>
</div>

<div class="mb-3">
<label for="featured-image" class="form-label fw-bold">Ảnh sản phẩm</label>
<input id="featured-image" type="file" name="featured_image" class="form-control"
       accept="image/jpeg,image/png,image/gif,image/webp,image/bmp,image/avif">
<div class="form-text">Chọn ảnh JPG, PNG, GIF, WEBP, BMP hoặc AVIF; dung lượng tối đa 5 MB.</div>
<img id="image-preview" class="img-thumbnail mt-3 d-none" alt="Xem trước ảnh sản phẩm"
     style="max-height: 220px;">
</div>

<button type="submit" class="btn btn-success w-100">
<i class="bi bi-check-circle"></i> Lưu sản phẩm
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
