<?php require "view/layout/header.php"; ?>

<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
    <div>
        <h1 class="h2 mb-1"><i class="bi bi-bag-heart me-2" aria-hidden="true"></i>Tất cả sản phẩm</h1>
        <p class="text-muted mb-0">Khám phá các sản phẩm chăm sóc da và làm đẹp.</p>
    </div>
    <a href="index.php" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Về trang chủ
    </a>
</div>

<?php if (empty($products)): ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2" aria-hidden="true"></i>Hiện chưa có sản phẩm nào.
    </div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($products as $product): ?>
            <div class="col-6 col-md-4 col-lg-3">
                <?php
                $cardProduct = $product;
                $compactProductCard = false;
                $showProductDescription = false;
                $productCardBadge = null;
                require "view/layout/product_card.php";
                ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require "view/layout/footer.php"; ?>
