<?php require "view/layout/header.php"; ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center gap-2 mb-4">
            <h1 class="h2 mb-0">Chỉnh sửa bài viết</h1>
            <a class="btn btn-outline-secondary" href="index.php?page=admin_posts">
                <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Quay lại
            </a>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="index.php?page=admin_update_post" enctype="multipart/form-data">
                    <input type="hidden" name="post_id" value="<?= (int) $post['post_id'] ?>">

                    <div class="mb-3">
                        <label for="post-title" class="form-label">Tiêu đề</label>
                        <input id="post-title" class="form-control" name="title" maxlength="255" required
                               value="<?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="post-content">Nội dung</label>
                        <textarea id="post-content" class="form-control" name="content" rows="12" required><?= htmlspecialchars($post['content'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="post-image" class="form-label">Ảnh bài viết</label>
                        <?php if (!empty($post['image_url'])): ?>
                            <div class="mb-2">
                                <img src="<?= htmlspecialchars($post['image_url'], ENT_QUOTES, 'UTF-8') ?>"
                                     class="img-thumbnail" alt="Ảnh bài viết hiện tại"
                                     style="max-height: 220px;"
                                     onerror="this.onerror=null;this.src='img/product-placeholder.svg';">
                            </div>
                        <?php endif; ?>
                        <input id="post-image" class="form-control" type="file" name="post_image"
                               accept="image/jpeg,image/png,image/gif,image/webp,image/bmp,image/avif">
                        <div class="form-text">Chọn ảnh mới nếu muốn thay ảnh hiện tại. JPG, PNG, GIF, WEBP, BMP hoặc AVIF; tối đa 5 MB.</div>
                        <img id="post-image-preview" class="img-thumbnail mt-3 d-none"
                             alt="Xem trước ảnh mới" style="max-height: 220px;">
                    </div>

                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-check-circle me-1" aria-hidden="true"></i>Lưu thay đổi
                    </button>
                    <a class="btn btn-outline-secondary ms-2" href="index.php?page=admin_posts">Hủy</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('post-image').addEventListener('change', function () {
    const preview = document.getElementById('post-image-preview');
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
