<?php require "view/layout/header.php"; ?>

<section class="posts-page">
    <header class="posts-page-header">
        <span class="posts-page-eyebrow"><i class="bi bi-stars me-1" aria-hidden="true"></i>Góc làm đẹp</span>
        <h1>Bài viết làm đẹp</h1>
        <p>Khám phá bí quyết chăm sóc da, trang điểm và lựa chọn mỹ phẩm phù hợp.</p>
    </header>

    <?php if (empty($posts)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2" aria-hidden="true"></i>Hiện chưa có bài viết nào.
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($posts as $post): ?>
                <?php
                $postTitle = (string) ($post['title'] ?? '');
                $postExcerpt = trim(strip_tags((string) ($post['content'] ?? '')));
                if (function_exists('mb_strlen') && function_exists('mb_substr')) {
                    $postExcerpt = mb_strlen($postExcerpt, 'UTF-8') > 145
                        ? mb_substr($postExcerpt, 0, 145, 'UTF-8') . '...'
                        : $postExcerpt;
                } elseif (strlen($postExcerpt) > 145) {
                    $postExcerpt = substr($postExcerpt, 0, 145) . '...';
                }
                $postImage = trim((string) ($post['image_url'] ?? ''));
                ?>
                <div class="col-sm-6 col-xl-4">
                    <article class="card post-card h-100 border-0">
                        <a class="post-card-image-link"
                           href="index.php?page=post_detail&amp;id=<?= (int) $post['post_id'] ?>"
                           aria-label="Đọc bài viết: <?= htmlspecialchars($postTitle, ENT_QUOTES, 'UTF-8') ?>">
                            <img src="<?= htmlspecialchars($postImage !== '' ? $postImage : 'img/product-placeholder.svg', ENT_QUOTES, 'UTF-8') ?>"
                                 class="post-card-image"
                                 alt="<?= htmlspecialchars($postTitle, ENT_QUOTES, 'UTF-8') ?>"
                                 loading="lazy"
                                 onerror="this.onerror=null;this.src='img/product-placeholder.svg';">
                        </a>
                        <div class="card-body post-card-body">
                            <div class="post-card-date">
                                <i class="bi bi-calendar3" aria-hidden="true"></i>
                                <?= !empty($post['created_at']) ? date('d/m/Y', strtotime($post['created_at'])) : '' ?>
                            </div>
                            <h2 class="post-card-title">
                                <a href="index.php?page=post_detail&amp;id=<?= (int) $post['post_id'] ?>">
                                    <?= htmlspecialchars($postTitle, ENT_QUOTES, 'UTF-8') ?>
                                </a>
                            </h2>
                            <p class="post-card-excerpt">
                                <?= htmlspecialchars($postExcerpt, ENT_QUOTES, 'UTF-8') ?>
                            </p>
                            <a href="index.php?page=post_detail&amp;id=<?= (int) $post['post_id'] ?>"
                               class="post-card-link mt-auto">
                                Đọc bài viết <i class="bi bi-arrow-right" aria-hidden="true"></i>
                            </a>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require "view/layout/footer.php"; ?>