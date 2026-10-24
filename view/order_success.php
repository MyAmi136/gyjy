<?php require "view/layout/header.php"; ?>

<div class="container text-center">

    <div class="alert alert-success mb-5">
        <h2 class="mb-3">✅ Đặt hàng thành công!</h2>
        <p class="fs-5">Cảm ơn bạn đã mua sắm tại cửa hàng của chúng tôi.</p>
    </div>

    <?php if(isset($_SESSION['last_order_id'])): ?>

        <div class="card shadow-sm p-4 mb-4" style="max-width: 600px; margin: 0 auto;">
            <h5>Chi tiết đơn hàng</h5>
            <p class="mb-2"><strong>Mã đơn hàng:</strong> #<?= $_SESSION['last_order_id'] ?></p>
            <p class="mb-3">
                <strong>Trạng thái:</strong> 
                <span class="badge bg-warning">Chờ xác nhận</span>
            </p>
            
            <!-- Payment Status -->
            <?php if(isset($_SESSION['payment_result'])): ?>
                <hr>
                <p class="mb-2">
                    <strong>Thanh toán trực tuyến:</strong>
                    <span class="badge bg-success">Thanh toán thành công</span>
                </p>
                <p class="text-muted small">
                    Mã giao dịch: <?= $_SESSION['payment_result']['transaction_id'] ?? 'N/A' ?>
                </p>
                <?php unset($_SESSION['payment_result']); ?>
            <?php else: ?>
                <hr>
                <p class="mb-2">
                    <strong>Thanh toán:</strong>
                    <span class="badge bg-info">Thanh toán khi nhận hàng (COD)</span>
                </p>
            <?php endif; ?>
            
            <hr>
            
            <p class="text-muted">
                Chúng tôi sẽ liên hệ với bạn sớm nhất để xác nhận đơn hàng.
            </p>
        </div>

    <?php unset($_SESSION['last_order_id']); endif; ?>

    <div class="mt-4 d-flex justify-content-center gap-2 flex-wrap">
        <a href="index.php" class="btn btn-primary btn-lg">
            <i class="bi bi-house"></i> Quay về trang chủ
        </a>
        <a href="index.php?page=products" class="btn btn-danger btn-lg">
            <i class="bi bi-bag"></i> Tiếp tục mua sắm
        </a>
        <a href="index.php?page=my_orders" class="btn btn-outline-primary btn-lg">
            <i class="bi bi-receipt"></i> Đơn hàng của tôi
        </a>
        <a href="index.php?page=posts" class="btn btn-secondary btn-lg">
            <i class="bi bi-book"></i> Xem bài viết
        </a>
    </div>

</div>

<?php require "view/layout/footer.php"; ?>
