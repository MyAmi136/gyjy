<?php require "view/layout/header.php"; ?>

<?php if (!$order): ?>
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-circle me-2" aria-hidden="true"></i>Không tìm thấy đơn hàng này.
    </div>
    <a href="index.php?page=my_orders" class="btn btn-outline-primary">
        <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Về đơn hàng của tôi
    </a>
<?php else: ?>
    <?php
    $statusLabels = [
        'pending' => ['Chờ xác nhận', 'bg-warning text-dark'],
        'processing' => ['Đang xử lý', 'bg-info text-dark'],
        'shipping' => ['Đang giao hàng', 'bg-primary'],
        'completed' => ['Hoàn thành', 'bg-success'],
        'cancelled' => ['Đã hủy', 'bg-secondary'],
    ];
    $paymentLabels = [
        'pending' => ['Chưa thanh toán', 'bg-warning text-dark'],
        'paid' => ['Đã thanh toán', 'bg-success'],
        'failed' => ['Thanh toán thất bại', 'bg-danger'],
    ];
    $orderStatus = $statusLabels[$order['status']] ?? ['Đang cập nhật', 'bg-secondary'];
    $paymentStatus = $paymentLabels[$order['payment_status'] ?? 'pending'] ?? ['Đang cập nhật', 'bg-secondary'];
    $calculatedTotal = 0;
    foreach ($orderItems as $item) {
        $calculatedTotal += (float) $item['price'] * (int) $item['quantity'];
    }
    ?>

    <section class="customer-order-detail">
        <a href="index.php?page=my_orders" class="customer-order-back">
            <i class="bi bi-arrow-left" aria-hidden="true"></i> Đơn hàng của tôi
        </a>

        <div class="customer-order-detail-header">
            <div>
                <span class="customer-orders-eyebrow">Chi tiết đơn hàng</span>
                <h1 class="h2 mb-1">Đơn hàng #<?= (int) $order['order_id'] ?></h1>
                <p class="text-muted mb-0">Đặt lúc <?= !empty($order['created_at']) ? date('d/m/Y H:i', strtotime($order['created_at'])) : '—' ?></p>
            </div>
            <span class="badge <?= $orderStatus[1] ?> customer-order-detail-status">
                <?= htmlspecialchars($orderStatus[0], ENT_QUOTES, 'UTF-8') ?>
            </span>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-lg-8">
                <div class="card customer-order-detail-card border-0">
                    <div class="card-body p-0">
                        <h2 class="h5 p-3 mb-0 border-bottom">Sản phẩm đã đặt</h2>
                        <?php if (empty($orderItems)): ?>
                            <p class="text-muted p-3 mb-0">Đơn hàng chưa có thông tin sản phẩm.</p>
                        <?php else: ?>
                            <div class="customer-order-items">
                                <?php foreach ($orderItems as $item): ?>
                                    <?php $subtotal = (float) $item['price'] * (int) $item['quantity']; ?>
                                    <div class="customer-order-item">
                                        <img src="<?= htmlspecialchars(trim((string) ($item['image_url'] ?? '')) !== '' ? $item['image_url'] : 'img/product-placeholder.svg', ENT_QUOTES, 'UTF-8') ?>"
                                             alt="<?= htmlspecialchars($item['product_name'] ?? 'Sản phẩm', ENT_QUOTES, 'UTF-8') ?>"
                                             onerror="this.onerror=null;this.src='img/product-placeholder.svg';">
                                        <div class="customer-order-item-info">
                                            <h3><?= htmlspecialchars($item['product_name'] ?? 'Sản phẩm đã ngừng kinh doanh', ENT_QUOTES, 'UTF-8') ?></h3>
                                            <span><?= number_format((float) $item['price']) ?> đ × <?= (int) $item['quantity'] ?></span>
                                        </div>
                                        <strong><?= number_format($subtotal) ?> đ</strong>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="customer-order-total-row">
                                <span>Tổng cộng</span>
                                <strong><?= number_format((float) $order['total_amount'] ?: $calculatedTotal) ?> đ</strong>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card customer-order-detail-card border-0 mb-4">
                    <div class="card-body">
                        <h2 class="h5 mb-3">Giao hàng</h2>
                        <div class="customer-order-address">
                            <i class="bi bi-geo-alt" aria-hidden="true"></i>
                            <span><?= nl2br(htmlspecialchars($order['shipping_address'] ?? '', ENT_QUOTES, 'UTF-8')) ?></span>
                        </div>
                    </div>
                </div>
                <div class="card customer-order-detail-card border-0">
                    <div class="card-body">
                        <h2 class="h5 mb-3">Thanh toán</h2>
                        <p class="mb-2">
                            Phương thức:
                            <strong><?= htmlspecialchars(strtoupper((string) ($order['payment_method'] ?? 'COD')), ENT_QUOTES, 'UTF-8') ?></strong>
                        </p>
                        <span class="badge <?= $paymentStatus[1] ?>">
                            <?= htmlspecialchars($paymentStatus[0], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php require "view/layout/footer.php"; ?>
