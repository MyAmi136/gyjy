<?php require "view/layout/header.php"; ?>

<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <h1 class="h4 alert-heading">💄 Chào mừng đến Cosmetics Shop!</h1>
    <p class="mb-0">Khám phá bộ sưu tập mỹ phẩm chính hãng của chúng tôi.</p>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
</div>

<section class="mb-5">
    <h2 class="h3 mb-4"><i class="bi bi-fire me-2" aria-hidden="true"></i>Sản phẩm bán chạy</h2>

    <?php if (!empty($bestsellers)): ?>
        <div class="row g-3">
            <?php foreach ($bestsellers as $product): ?>
                <div class="col-6 col-md-4 col-lg-2">
                    <?php
                    $cardProduct = $product;
                    $compactProductCard = true;
                    $showProductDescription = false;
                    $soldCount = (int) ($product['total_sold'] ?? 0);
                    $productCardBadge = $soldCount > 0 ? 'BÁN CHẠY (' . $soldCount . ')' : 'NỔI BẬT';
                    require "view/layout/product_card.php";
                    ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="index.php?page=products" class="btn btn-outline-danger btn-lg">
                Xem tất cả sản phẩm <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    <?php else: ?>
        <div class="alert alert-info mb-0">
            <i class="bi bi-info-circle me-2" aria-hidden="true"></i>Chưa có dữ liệu sản phẩm bán chạy.
        </div>
    <?php endif; ?>
</section>

<hr class="my-5">

<section>
    <h2 class="h3 mb-4"><i class="bi bi-star me-2" aria-hidden="true"></i>Sản phẩm nổi bật</h2>

    <?php if (!empty($products)): ?>
        <div class="row g-4">
            <?php foreach ($products as $product): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <?php
                    $cardProduct = $product;
                    $compactProductCard = false;
                    $showProductDescription = true;
                    $productCardBadge = null;
                    require "view/layout/product_card.php";
                    ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info mb-0">
            <i class="bi bi-info-circle me-2" aria-hidden="true"></i>Chưa có sản phẩm nào.
        </div>
    <?php endif; ?>
</section>

<?php require "view/layout/footer.php"; ?>
