<?php require 'view/layout/header.php'; ?>

<div class="row justify-content-center">
<div class="col-md-5">

<div class="card shadow">

<div class="card-body">

<h4 class="text-center mb-4">
Đăng ký tài khoản
</h4>

<form method="POST">
<input type="hidden" name="redirect"
       value="<?= in_array($_GET['redirect'] ?? '', ['checkout', 'my_orders'], true) ? htmlspecialchars($_GET['redirect'], ENT_QUOTES, 'UTF-8') : '' ?>">

<div class="mb-3">
<label class="form-label">Tên tài khoản</label>
<input class="form-control"
       name="username"
       placeholder="Username" required>
</div>

<div class="mb-3">
<label class="form-label">Họ tên</label>
<input class="form-control"
       name="full_name"
       placeholder="Họ tên" required>
</div>

<div class="mb-3">
<label class="form-label">Email</label>
<input class="form-control"
       name="email"
       type="email"
       placeholder="Email" required>
</div>

<div class="mb-3">
<label class="form-label">Mật khẩu</label>
<input type="password"
       class="form-control"
       name="password"
       placeholder="Mật khẩu" required>
</div>

<button class="btn btn-primary w-100 py-2">
<i class="bi bi-person-plus"></i> Đăng ký
</button>

</form>

<hr>

<div class="text-center">

Đã có tài khoản?

<a href="index.php?page=login<?= in_array($_GET['redirect'] ?? '', ['checkout', 'my_orders'], true) ? '&amp;redirect=' . rawurlencode($_GET['redirect']) : '' ?>">
Đăng nhập
</a>

</div>

<div class="text-center mt-3">
<a href="index.php" class="btn btn-outline-secondary btn-sm">
    <i class="bi bi-arrow-left"></i> Quay lại trang chủ
</a>
</div>

</div>
</div>

</div>
</div>

<?php require 'view/layout/footer.php'; ?>
