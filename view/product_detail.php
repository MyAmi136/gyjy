<?php require "view/layout/header.php"; ?>


<div class="mb-3">

    <a href="index.php?page=products" class="btn btn-outline-secondary btn-sm">

        <i class="bi bi-arrow-left"></i> Quay lại danh sách sản phẩm

    </a>

</div>


<?php if(!$product): ?>
    <div class="alert alert-danger">Không tìm thấy sản phẩm.</div>
    <a href="index.php?page=products" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Xem các sản phẩm khác
    </a>
<?php else: ?>
    <?php
    $productName = (string) ($product['product_name'] ?? '');
    $productImage = trim((string) ($product['featured_image_url'] ?? ''));
    $stock = isset($product['stock']) ? (int) $product['stock'] : 0;
    $isOutOfStock = $stock <= 0;
    ?>
    <div class="row g-4 align-items-start">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="p-3">
                    <img id="mainImage"
                         src="<?= htmlspecialchars($productImage !== '' ? $productImage : 'img/product-placeholder.svg', ENT_QUOTES, 'UTF-8') ?>"
                         class="img-fluid rounded w-100"
                         style="height: 390px; object-fit: contain;"
                         alt="<?= htmlspecialchars($productName, ENT_QUOTES, 'UTF-8') ?>"
                         onerror="this.onerror=null;this.src='img/product-placeholder.svg';">
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <?php if(!empty($product['category_name'])): ?>
                <span class="badge text-bg-light border mb-2">
                    <?= htmlspecialchars($product['category_name'], ENT_QUOTES, 'UTF-8') ?>
                </span>
            <?php endif; ?>

            <h1 class="h2 fw-semibold"><?= htmlspecialchars($productName, ENT_QUOTES, 'UTF-8') ?></h1>
            <p class="h3 text-danger fw-bold mb-3">
                <?= number_format((float) ($product['price'] ?? 0)) ?>đ
            </p>

            <div class="card bg-light border-0 mb-3">
                <div class="card-body">
                    <h2 class="h5"><i class="bi bi-card-text me-1"></i>Giới thiệu sản phẩm</h2>
                    <p class="mb-0">
                        <?= nl2br(htmlspecialchars(trim((string) ($product['description'] ?? '')) !== '' ? $product['description'] : 'Thông tin mô tả đang được cập nhật.', ENT_QUOTES, 'UTF-8')) ?>
                    </p>
                </div>
            </div>


            <div class="alert <?= $isOutOfStock ? 'alert-secondary' : 'alert-info' ?> d-flex align-items-center gap-2">
                <i class="bi <?= $isOutOfStock ? 'bi-box-seam' : 'bi-check-circle' ?> fs-5"></i>
                <span><?= $isOutOfStock ? 'Sản phẩm hiện đang hết hàng.' : 'Còn ' . $stock . ' sản phẩm trong kho.' ?></span>
            </div>

            <form method="POST" action="index.php?page=add-cart">
                <input type="hidden" name="id" value="<?= (int) $product['product_id'] ?>">

                <div class="mb-3">
                    <label class="form-label fw-bold" for="detailQty">Số lượng</label>
                    <div class="input-group" style="max-width: 180px;">
                        <button class="btn btn-outline-secondary" type="button" aria-label="Giảm số lượng"
                                onclick="var el=document.getElementById('detailQty'); if(parseInt(el.value,10)>1) el.stepDown();">−</button>
                        <input type="number" id="detailQty" name="qty" value="1" min="1"
                               max="<?= max(1, $stock) ?>" class="form-control text-center"
                               <?= $isOutOfStock ? 'disabled' : '' ?>>
                        <button class="btn btn-outline-secondary" type="button" aria-label="Tăng số lượng"
                                onclick="var el=document.getElementById('detailQty'); if(parseInt(el.value,10)<parseInt(el.max,10)) el.stepUp();"
                                <?= $isOutOfStock ? 'disabled' : '' ?>>+</button>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <?php if(!$isOutOfStock): ?>
                        <button type="submit" name="action" value="add" class="btn btn-success btn-lg">
                            <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                        </button>
                        <button type="submit" name="action" value="buy_now" class="btn btn-danger btn-lg">
                            <i class="bi bi-lightning-charge"></i> Mua ngay
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn btn-secondary btn-lg" disabled>
                            <i class="bi bi-exclamation-circle"></i> Hết hàng
                        </button>
                    <?php endif; ?>
                    <a href="index.php?page=products" class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-arrow-left"></i> Tiếp tục xem sản phẩm
                    </a>
                </div>
            </form>
        </div>
    </div>

    <section class="card shadow-sm border-0 mt-4 mb-4" aria-labelledby="product-guidance-title">
        <div class="card-body p-4">
            <h2 id="product-guidance-title" class="h4 mb-3">
                <i class="bi bi-info-circle text-primary me-2"></i><?= htmlspecialchars($guidance['title'], ENT_QUOTES, 'UTF-8') ?>
            </h2>
            <p class="text-muted"><?= htmlspecialchars($guidance['fit'], ENT_QUOTES, 'UTF-8') ?></p>
            <h3 class="h6 fw-bold">Cách sử dụng tham khảo</h3>
            <ol class="mb-3">
                <?php foreach($guidance['steps'] as $step): ?>
                    <li class="mb-1"><?= htmlspecialchars($step, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ol>
            <p class="small text-secondary mb-0">
                <i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($guidance['note'], ENT_QUOTES, 'UTF-8') ?>
            </p>
        </div>
    </section>

<?php endif; ?>

<?php require "view/layout/footer.php"; ?>
