<?php require "view/layout/header.php"; ?>



<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
    <h2 class="mb-0">
        <i class="bi bi-receipt"></i> Quản lý đơn hàng
    </h2>
    <div class="d-flex gap-2 flex-wrap">
        <button type="button" class="btn btn-secondary" onclick="window.history.length > 1 ? window.history.back() : window.location.href='index.php?page=admin_dashboard';">
            <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Quay lại trang trước
        </button>
        <a class="btn btn-outline-secondary" href="index.php?page=admin_dashboard">
            <i class="bi bi-speedometer2 me-1" aria-hidden="true"></i>Về trang quản trị
        </a>
    </div>
</div>



<div class="card shadow">



<div class="table-responsive">



<table class="table table-hover align-middle mb-0">



<thead class="table-dark">

<tr>

<th>ID</th>

<th>Khách hàng</th>

<th>Ngày đặt</th>

<th>Tổng tiền</th>

<th>Trạng thái</th>

<th>Thao tác</th>

</tr>

</thead>



<tbody>



<?php

$statusLabels = [

    'pending' => ['Chờ xử lý', 'bg-warning text-dark'],

    'processing' => ['Đang xử lý', 'bg-info text-dark'],

    'shipping' => ['Đang giao', 'bg-primary'],

    'completed' => ['Hoàn thành', 'bg-success'],

    'paid' => ['Đã thanh toán', 'bg-success'],

    'cancelled' => ['Đã hủy', 'bg-secondary']

];

?>



<?php if (empty($orders)): ?>

<tr>

    <td colspan="6" class="py-4 text-center text-muted">Chưa có đơn hàng nào.</td>

</tr>

<?php else: ?>

<?php foreach($orders as $o): ?>



<tr>



<td class="fw-bold">

#<?= (int) $o['order_id'] ?>

</td>



<td>

<i class="bi bi-person"></i>

<?= htmlspecialchars($o['full_name'] ?? 'Khách hàng', ENT_QUOTES, 'UTF-8') ?>

</td>



<td>

<?= !empty($o['created_at']) ? date('d/m/Y H:i', strtotime($o['created_at'])) : '—' ?>

</td>



<td class="text-danger fw-bold">

<?= number_format($o['total_amount']) ?> đ

</td>



<td>

<?php $status = $statusLabels[$o['status']] ?? [htmlspecialchars($o['status'], ENT_QUOTES, 'UTF-8'), 'bg-secondary']; ?>

<span class="badge <?= $status[1] ?>"><?= $status[0] ?></span>

</td>



<td>



<a href="index.php?page=admin_order_detail&amp;id=<?= (int) $o['order_id'] ?>"

class="btn btn-primary btn-sm">

Chi tiết

</a>



</td>



</tr>



<?php endforeach; ?>

<?php endif; ?>



</tbody>



</table>



</div>

</div>



<?php require "view/layout/footer.php"; ?>