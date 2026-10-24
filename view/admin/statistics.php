<?php require "view/layout/header.php"; ?>

<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
    <h1 class="h2 mb-0"><i class="bi bi-graph-up me-2" aria-hidden="true"></i>Thống kê bán hàng</h1>
    <div class="d-flex gap-2 flex-wrap">
        <button type="button" class="btn btn-secondary" onclick="window.history.length > 1 ? window.history.back() : window.location.href='index.php?page=admin_dashboard';">
            <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Quay lại trang trước
        </button>
        <a class="btn btn-outline-secondary" href="index.php?page=admin_dashboard">
            <i class="bi bi-speedometer2 me-1" aria-hidden="true"></i>Về trang quản trị
        </a>
    </div>
</div>

<form class="card border-0 shadow-sm mb-4" method="GET" action="index.php">
    <input type="hidden" name="page" value="admin_statistics">
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4 col-lg-3">
                <label for="statistics-period" class="form-label fw-semibold">Lọc thống kê theo</label>
                <select id="statistics-period" name="period" class="form-select">
                    <?php
                    $periodLabels = [
                        'day' => 'Ngày',
                        'week' => 'Tuần',
                        'month' => 'Tháng',
                        'quarter' => 'Quý',
                        'year' => 'Năm'
                    ];
                    foreach ($periodLabels as $value => $label):
                    ?>
                        <option value="<?= $value ?>" <?= $periodType === $value ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-5 col-lg-4 statistics-period-input" data-period-input="day">
                <label for="statistics-date" class="form-label">Chọn ngày</label>
                <input id="statistics-date" class="form-control" type="date" name="period_date"
                       value="<?= htmlspecialchars($selectedDate, ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="col-md-5 col-lg-4 statistics-period-input" data-period-input="week">
                <label for="statistics-week-date" class="form-label">Chọn một ngày trong tuần</label>
                <input id="statistics-week-date" class="form-control" type="date" name="period_date"
                       value="<?= htmlspecialchars($selectedDate, ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="col-md-5 col-lg-4 statistics-period-input" data-period-input="month">
                <label for="statistics-month" class="form-label">Chọn tháng</label>
                <input id="statistics-month" class="form-control" type="month" name="period_month"
                       value="<?= htmlspecialchars($selectedMonth, ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="col-md-3 col-lg-2 statistics-period-input" data-period-input="quarter">
                <label for="statistics-quarter" class="form-label">Quý</label>
                <select id="statistics-quarter" class="form-select" name="period_quarter">
                    <?php for ($quarter = 1; $quarter <= 4; $quarter++): ?>
                        <option value="<?= $quarter ?>" <?= $selectedQuarter === $quarter ? 'selected' : '' ?>>
                            Quý <?= $quarter ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-3 col-lg-2 statistics-period-input" data-period-input="quarter">
                <label for="statistics-quarter-year" class="form-label">Năm</label>
                <select id="statistics-quarter-year" class="form-select" name="period_year">
                    <?php for ($year = (int) date('Y') + 1; $year >= 2000; $year--): ?>
                        <option value="<?= $year ?>" <?= $selectedYear === $year ? 'selected' : '' ?>>
                            <?= $year ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-5 col-lg-3 statistics-period-input" data-period-input="year">
                <label for="statistics-year" class="form-label">Chọn năm</label>
                <select id="statistics-year" class="form-select" name="period_year">
                    <?php for ($year = (int) date('Y') + 1; $year >= 2000; $year--): ?>
                        <option value="<?= $year ?>" <?= $selectedYear === $year ? 'selected' : '' ?>>
                            <?= $year ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="col-md-3 col-lg-2">
                <button class="btn btn-primary w-100" type="submit">
                    <i class="bi bi-funnel me-1" aria-hidden="true"></i>Lọc
                </button>
            </div>
        </div>
    </div>
</form>

<h2 class="h5 mb-3">Kết quả: <?= htmlspecialchars($periodLabel, ENT_QUOTES, 'UTF-8') ?></h2>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Số đơn hàng</div>
                <div class="fs-3 fw-bold"><?= number_format($stats['total_orders'] ?? 0) ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Sản phẩm đã bán</div>
                <div class="fs-3 fw-bold text-primary"><?= number_format($stats['total_products_sold'] ?? 0) ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Doanh thu</div>
                <div class="fs-4 fw-bold text-success"><?= number_format($stats['total_revenue'] ?? 0) ?> đ</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Giá trị đơn trung bình</div>
                <div class="fs-4 fw-bold text-info"><?= number_format($stats['avg_order_value'] ?? 0) ?> đ</div>
            </div>
        </div>
    </div>
</div>

<section class="mb-5">
    <h2 class="h4 mb-3">Chi tiết <?= htmlspecialchars($periodLabel, ENT_QUOTES, 'UTF-8') ?></h2>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Thời gian</th>
                        <th scope="col">Số đơn</th>
                        <th scope="col">Số sản phẩm</th>
                        <th scope="col">Doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($periodStats)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Không có đơn hàng trong khoảng thời gian này.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($periodStats as $period): ?>
                            <tr>
                                <th scope="row"><?= htmlspecialchars((string) $period['period'], ENT_QUOTES, 'UTF-8') ?></th>
                                <td><?= number_format($period['orders']) ?></td>
                                <td><?= number_format($period['products_sold']) ?></td>
                                <td class="fw-semibold text-success"><?= number_format($period['revenue']) ?> đ</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="mb-4">
    <h2 class="h4 mb-3">Sản phẩm bán chạy trong khoảng thời gian đã chọn</h2>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Sản phẩm</th>
                        <th scope="col">Số lượng bán</th>
                        <th scope="col">Doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($top_products)): ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Không có sản phẩm đã bán trong khoảng thời gian này.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($top_products as $product): ?>
                            <tr>
                                <th scope="row"><?= htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8') ?></th>
                                <td><?= number_format($product['total_sold']) ?></td>
                                <td class="fw-semibold text-success"><?= number_format($product['revenue']) ?> đ</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
const statisticsPeriod = document.getElementById('statistics-period');
const statisticsInputs = document.querySelectorAll('[data-period-input]');

function updateStatisticsInputs() {
    const activePeriod = statisticsPeriod.value;
    statisticsInputs.forEach(function (group) {
        const visible = group.dataset.periodInput === activePeriod;
        group.classList.toggle('d-none', !visible);
        group.querySelectorAll('input, select').forEach(function (input) {
            input.disabled = !visible;
        });
    });
}

statisticsPeriod.addEventListener('change', updateStatisticsInputs);
updateStatisticsInputs();
</script>

<?php require "view/layout/footer.php"; ?>
