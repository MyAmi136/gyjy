<?php require "view/layout/header.php"; ?>

<?php
$statusLabels = [
    'pending' => ['Chờ xác nhận', 'bg-warning text-dark'],
    'paid' => ['Đã thanh toán', 'bg-success'],
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
?>

<section class="customer-orders-page">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
        <div>
            <span class="customer-orders-eyebrow"><i class="bi bi-bag-heart me-1" aria-hidden="true"></i>Tài khoản của bạn</span>
            <h1 class="h2 mb-1">Đơn hàng của tôi</h1>
            <p class="text-muted mb-0">Theo dõi trạng thái và xem lại các đơn hàng bạn đã đặt.</p>
        </div>
        <a href="index.php?page=products" class="btn btn-outline-primary">
            <i class="bi bi-bag me-1" aria-hidden="true"></i>Tiếp tục mua sắm
        </a>
    </div>

    <?php if (empty($orders)): ?>
        <div class="card customer-orders-empty border-0">
            <div class="card-body text-center py-5">
                <i class="bi bi-receipt-cutoff customer-orders-empty-icon" aria-hidden="true"></i>
                <h2 class="h5 mt-3">Bạn chưa có đơn hàng nào</h2>
                <p class="text-muted">Các đơn hàng sau khi đặt sẽ được lưu tại đây để bạn tiện theo dõi.</p>
                <a href="index.php?page=products" class="btn btn-primary">
                    <i class="bi bi-bag me-1" aria-hidden="true"></i>Khám phá sản phẩm
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="customer-order-list">
            <?php foreach ($orders as $order): ?>
                <?php
                $orderStatus = $statusLabels[$order['status']] ?? ['Đang cập nhật', 'bg-secondary'];
                $paymentStatus = $paymentLabels[$order['payment_status'] ?? 'pending'] ?? ['Đang cập nhật', 'bg-secondary'];
                ?>
                <article class="card customer-order-card border-0">
                    <div class="card-body">
                        <div class="customer-order-heading">
                            <div>
                                <span class="customer-order-label">Mã đơn hàng</span>
                                <h2 class="h5 mb-1">#<?= (int) $order['order_id'] ?></h2>
                            </div>
                            <div class="customer-order-total">
                                <span class="customer-order-label">Tổng cộng</span>
                                <strong><?= number_format((float) $order['total_amount']) ?> đ</strong>
                            </div>
                        </div>

                        <div class="customer-order-meta">
                            <span><i class="bi bi-calendar3 me-1" aria-hidden="true"></i><?= !empty($order['created_at']) ? date('d/m/Y H:i', strtotime($order['created_at'])) : '—' ?></span>
                            <span><i class="bi bi-credit-card me-1" aria-hidden="true"></i><?= htmlspecialchars(strtoupper((string) ($order['payment_method'] ?? 'COD')), ENT_QUOTES, 'UTF-8') ?></span>
                        </div>

                        <div class="customer-order-badges">
                            <span class="badge <?= $orderStatus[1] ?>"><?= htmlspecialchars($orderStatus[0], ENT_QUOTES, 'UTF-8') ?></span>
                            <span class="badge <?= $paymentStatus[1] ?>"><?= htmlspecialchars($paymentStatus[0], ENT_QUOTES, 'UTF-8') ?></span>
                        </div>

                        <div class="text-end mt-3">
                            <a href="index.php?page=my_order_detail&amp;id=<?= (int) $order['order_id'] ?>"
                               class="btn btn-outline-primary btn-sm">
                                Xem chi tiết <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require "view/layout/footer.php"; ?>
