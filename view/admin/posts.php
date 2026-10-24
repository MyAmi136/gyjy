<?php require "view/layout/header.php"; ?>

<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
    <h1 class="h2 mb-0"><i class="bi bi-journal-text me-2" aria-hidden="true"></i>Quản lý bài viết</h1>
    <div class="d-flex gap-2 flex-wrap">
        <button type="button" class="btn btn-secondary" onclick="window.history.length > 1 ? window.history.back() : window.location.href='index.php?page=admin_dashboard';">
            <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Quay lại trang trước
        </button>
        <a class="btn btn-outline-secondary" href="index.php?page=admin_dashboard">
            <i class="bi bi-speedometer2 me-1" aria-hidden="true"></i>Về trang quản trị
        </a>
    </div>
</div>

<?php foreach (['success' => 'success', 'error' => 'danger'] as $key => $type): ?>
    <?php if (isset($_SESSION[$key])): ?>
        <div class="alert alert-<?= $type ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION[$key], ENT_QUOTES, 'UTF-8') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
        <?php unset($_SESSION[$key]); ?>
    <?php endif; ?>
<?php endforeach; ?>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Đăng bài viết mới</h2>
                <form method="POST" action="index.php?page=admin_save_post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="post-title" class="form-label">Tiêu đề</label>
                        <input id="post-title" class="form-control" name="title" maxlength="255" required>
                    </div>
                    <div class="mb-3">
                        <label for="post-image" class="form-label">Ảnh bài viết</label>
                        <input id="post-image" class="form-control" type="file" name="post_image"
                               accept="image/jpeg,image/png,image/gif,image/webp,image/bmp,image/avif">
                        <div class="form-text">Ảnh JPG, PNG, GIF, WEBP, BMP hoặc AVIF; dung lượng tối đa 5 MB.</div>
                        <img id="post-image-preview" class="img-thumbnail mt-3 d-none"
                             alt="Xem trước ảnh bài viết" style="max-height: 220px;">
                    </div>
                    <div class="mb-3">
                        <label for="post-content" class="form-label">Nội dung</label>
                        <textarea id="post-content" class="form-control" name="content" rows="9" required></textarea>
                    </div>
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-send me-1" aria-hidden="true"></i>Đăng bài
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h2 class="h5 mb-3">Bài viết đã đăng</h2>
                <?php if (empty($posts)): ?>
                    <p class="text-muted mb-0">Chưa có bài viết nào.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Tiêu đề</th>
                                    <th scope="col">Ngày đăng</th>
                                    <th scope="col">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($posts as $post): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($post['image_url'])): ?>
                                                <img src="<?= htmlspecialchars($post['image_url'], ENT_QUOTES, 'UTF-8') ?>"
                                                     class="rounded mb-2" alt=""
                                                     style="width: 72px; height: 56px; object-fit: cover;"
                                                     onerror="this.onerror=null;this.src='img/product-placeholder.svg';">
                                            <?php endif; ?>
                                            <a href="index.php?page=post_detail&amp;id=<?= (int) $post['post_id'] ?>"
                                               class="fw-semibold text-decoration-none">
                                                <?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?>
                                            </a>
                                            <div class="small text-muted">
                                                <?= htmlspecialchars(substr(strip_tags($post['content'] ?? ''), 0, 90), ENT_QUOTES, 'UTF-8') ?>
                                            </div>
                                        </td>
                                        <td class="text-nowrap">
                                            <?= !empty($post['created_at']) ? date('d/m/Y', strtotime($post['created_at'])) : '—' ?>
                                        </td>
                                        <td>
                                            <a class="btn btn-outline-primary btn-sm mb-1"
                                               href="index.php?page=admin_edit_post&amp;id=<?= (int) $post['post_id'] ?>"
                                               aria-label="Sửa bài viết <?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?>">
                                                <i class="bi bi-pencil" aria-hidden="true"></i>
                                                <span class="visually-hidden">Sửa</span>
                                            </a>
                                            <form method="POST" action="index.php?page=admin_delete_post"
                                                  onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?');">
                                                <input type="hidden" name="post_id" value="<?= (int) $post['post_id'] ?>">
                                                <button class="btn btn-outline-danger btn-sm" type="submit">
                                                    <i class="bi bi-trash" aria-hidden="true"></i>
                                                    <span class="visually-hidden">Xóa bài viết</span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
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
