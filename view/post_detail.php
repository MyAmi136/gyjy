<?php require "view/layout/header.php"; ?>

<?php if (!$post): ?>
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-circle me-2" aria-hidden="true"></i>Không tìm thấy bài viết.
    </div>
    <a href="index.php?page=posts" class="btn btn-outline-primary">
        <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Về danh sách bài viết
    </a>
<?php else: ?>
    <div class="post-detail-wrap">
        <a href="index.php?page=posts" class="post-back-link">
            <i class="bi bi-arrow-left" aria-hidden="true"></i> Tất cả bài viết
        </a>

        <article class="card post-detail-card border-0">
            <header class="post-detail-header">
                <span class="posts-page-eyebrow"><i class="bi bi-stars me-1" aria-hidden="true"></i>Góc làm đẹp</span>
                <h1><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></h1>
                <?php if (!empty($post['created_at'])): ?>
                    <div class="post-detail-date">
                        <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>
                        <?= date('d/m/Y · H:i', strtotime($post['created_at'])) ?>
                    </div>
                <?php endif; ?>
            </header>

            <?php if (!empty($post['image_url'])): ?>
                <div class="post-detail-image-wrap">
                    <img src="<?= htmlspecialchars($post['image_url'], ENT_QUOTES, 'UTF-8') ?>"
                         class="post-detail-image"
                         alt="<?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?>"
                         onerror="this.onerror=null;this.src='img/product-placeholder.svg';">
                </div>
            <?php endif; ?>

            <div class="post-detail-content">
                <?= nl2br(htmlspecialchars($post['content'] ?? '', ENT_QUOTES, 'UTF-8')) ?>
            </div>
        </article>

        <div class="post-detail-footer">
            <a href="index.php?page=posts" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Quay lại danh sách
            </a>
        </div>
    </div>
<?php endif; ?>

<?php require "view/layout/footer.php"; ?>
