<?php
$cardImage = trim((string) ($cardProduct['featured_image_url'] ?? ''));
$cardIsCompact = !empty($compactProductCard);
$cardShowDescription = !empty($showProductDescription);
$cardBadge = $productCardBadge ?? null;
$cardProductName = (string) ($cardProduct['product_name'] ?? '');
$cardProductId = (int) ($cardProduct['product_id'] ?? 0);
$cardStock = isset($cardProduct['stock']) ? (int) $cardProduct['stock'] : null;
?>

<article class="card product-card h-100 shadow-sm border-0 overflow-hidden <?= $cardIsCompact ? 'product-card-compact text-center' : '' ?>">
    <div class="product-card-image-frame">
        <img
            src="<?= htmlspecialchars($cardImage !== '' ? $cardImage : 'img/product-placeholder.svg', ENT_QUOTES, 'UTF-8') ?>"
            class="product-card-image"
            alt="<?= htmlspecialchars($cardProductName, ENT_QUOTES, 'UTF-8') ?>"
            loading="lazy"
            onerror="this.onerror=null;this.src='img/product-placeholder.svg';">
    </div>

    <div class="card-body product-card-body">
        <?php if ($cardBadge !== null): ?>
            <div class="product-card-label">
                <span class="badge bg-danger"><?= htmlspecialchars($cardBadge, ENT_QUOTES, 'UTF-8') ?></span>
            </div>
        <?php endif; ?>

        <h3 class="product-card-title">
            <?= htmlspecialchars($cardProductName, ENT_QUOTES, 'UTF-8') ?>
        </h3>

        <div class="product-card-stock">
            <?php if ($cardStock !== null && $cardStock <= 0): ?>
                <span class="badge bg-secondary">Hết hàng</span>
            <?php elseif ($cardStock !== null && $cardStock < 10): ?>
                <span class="badge bg-warning text-dark">Sắp hết<?= $cardIsCompact ? '' : ' (' . $cardStock . ')' ?></span>
            <?php else: ?>
                <span class="badge bg-success">Còn hàng</span>
            <?php endif; ?>
        </div>

        <?php if ($cardShowDescription): ?>
            <p class="product-card-description text-muted">
                <?= htmlspecialchars(substr((string) ($cardProduct['description'] ?? ''), 0, 60), ENT_QUOTES, 'UTF-8') ?>
                <?= strlen((string) ($cardProduct['description'] ?? '')) > 60 ? '...' : '' ?>
            </p>
        <?php endif; ?>

        <p class="product-card-price text-danger fw-bold">
            <?= number_format((float) ($cardProduct['price'] ?? 0)) ?> đ
        </p>

        <div class="product-card-actions d-grid gap-2">
            <a class="btn btn-outline-primary btn-sm"
               href="index.php?page=product_detail&amp;id=<?= $cardProductId ?>">
                <i class="bi bi-eye" aria-hidden="true"></i> Chi tiết
            </a>
            <?php if ($cardStock === null || $cardStock > 0): ?>
                <a class="btn btn-danger btn-sm"
                   href="index.php?page=add-cart&amp;id=<?= $cardProductId ?>&amp;qty=1">
                    <i class="bi bi-cart-plus" aria-hidden="true"></i> Thêm giỏ
                </a>
            <?php else: ?>
                <button class="btn btn-secondary btn-sm" type="button" disabled>
                    <i class="bi bi-exclamation-circle" aria-hidden="true"></i> Hết hàng
                </button>
            <?php endif; ?>
        </div>
    </div>
</article>
