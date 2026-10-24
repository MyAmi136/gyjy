<?php require "view/layout/header.php"; ?>

<div class="container text-center py-5">
    <?php if ($payment['payment_status'] === 'paid'): ?>
        <div class="alert alert-success">
            <h2>Thanh toán thành công</h2>
            <p class="mb-0">Hệ thống đã xác nhận giao dịch cho đơn hàng #<?= (int) $orderId ?>.</p>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <h2>Đang chờ ngân hàng xác nhận</h2>
            <p class="mb-0">Đơn hàng #<?= (int) $orderId ?> sẽ tự cập nhật sau khi PayOS gửi xác nhận thanh toán. Vui lòng tải lại trang sau ít phút.</p>
        </div>
    <?php endif; ?>

    <a href="index.php?page=my_order_detail&amp;id=<?= (int) $orderId ?>" class="btn btn-primary">
        Xem chi tiết đơn hàng
    </a>
</div>

<?php require "view/layout/footer.php"; ?>
