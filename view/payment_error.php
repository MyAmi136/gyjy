<?php require "view/layout/header.php"; ?>

<div class="container text-center">

    <div class="alert alert-danger mb-5">
        <h2 class="mb-3">❌ Thanh toán thất bại!</h2>
        <p class="fs-5"><?= $error ?? 'Có lỗi xảy ra trong quá trình thanh toán.' ?></p>
    </div>

    <?php if(!empty($order_id)): ?>
        <div class="card shadow-sm p-4 mb-4" style="max-width: 500px; margin: 0 auto;">
            <h5>Thông tin đơn hàng</h5>
            <p class="mb-1"><strong>Mã đơn hàng:</strong> #<?= $order_id ?></p>
            <p class="text-warning"><strong>Trạng thái:</strong> Chờ thanh toán</p>
            
            <hr>
            
            <p class="text-muted">
                Bạn có thể thử lại hoặc liên hệ với chúng tôi để được hỗ trợ.
            </p>
        </div>
    <?php endif; ?>

    <div class="mt-4">
        <?php if (!empty($retryPayOS) && !empty($order_id)): ?>
            <a href="index.php?page=payos-retry&amp;order_id=<?= (int) $order_id ?>" class="btn btn-warning btn-lg">
                <i class="bi bi-arrow-clockwise"></i> Thử tạo lại giao dịch
            </a>
        <?php else: ?>
            <a href="index.php?page=checkout" class="btn btn-warning btn-lg">
                <i class="bi bi-arrow-left"></i> Thử lại thanh toán
            </a>
        <?php endif; ?>
        
        <a href="index.php" class="btn btn-secondary btn-lg ms-2">
            <i class="bi bi-house"></i> Quay về trang chủ
        </a>
    </div>

</div>

<?php require "view/layout/footer.php"; ?>
