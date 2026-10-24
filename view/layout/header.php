<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Cosmetics Shop</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="style/style.css?v=20261023-fixed-footer">

</head>

<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-danger shadow-sm">

<div class="container">

<!-- LOGO -->
<a class="navbar-brand fw-bold" href="index.php">
💄 Cosmetics Shop
</a>

<button class="navbar-toggler"
type="button"
data-bs-toggle="collapse"
data-bs-target="#menu">

<span class="navbar-toggler-icon"></span>

</button>


<div class="collapse navbar-collapse" id="menu">

<!-- LEFT MENU -->
<ul class="navbar-nav me-auto">

<li class="nav-item">
<a class="nav-link" href="index.php">Trang chủ</a>
</li>

<li class="nav-item">
<a class="nav-link" href="index.php?page=products">Sản phẩm</a>
</li>

<li class="nav-item">
<a class="nav-link" href="index.php?page=posts">Bài viết</a>
</li>

<!-- CATEGORY -->
<?php
require_once "model/Category.php";
$catModel = new Category();
$parents = $catModel->getParent();
?>

<li class="nav-item dropdown">

<a class="nav-link dropdown-toggle"
href="#"
role="button"
data-bs-toggle="dropdown">

Danh mục

</a>

<ul class="dropdown-menu">

<?php foreach($parents as $p){ ?>

<li>

<a class="dropdown-item fw-bold"
href="index.php?page=category&id=<?= $p['category_id'] ?>">

<?= $p['category_name'] ?>

</a>

<?php
$children = $catModel->getChild($p['category_id']);
foreach($children as $c){
?>

<a class="dropdown-item ps-4"
href="index.php?page=category&id=<?= $c['category_id'] ?>">

- <?= $c['category_name'] ?>

</a>

<?php } ?>

</li>

<?php } ?>

</ul>

</li>

</ul>


<!-- RIGHT MENU -->
<ul class="navbar-nav align-items-center">

<?php
$count = 0;

if(isset($_SESSION['cart'])){
foreach($_SESSION['cart'] as $item){
$count += $item['qty'];
}
}
?>

<li class="nav-item me-3">

<a class="btn btn-light position-relative"
href="index.php?page=cart">

🛒 Giỏ hàng

<span class="position-absolute top-0 start-100 translate-middle badge bg-danger">

<?= $count ?>

</span>

</a>

</li>

<?php if(isset($_SESSION['user'])): ?>

<li class="nav-item me-2">

<span class="navbar-text text-white">

👋 <?= $_SESSION['user']['full_name'] ?>

</span>

</li>

<li class="nav-item me-2">
<a class="btn btn-outline-light" href="index.php?page=my_orders">
<i class="bi bi-receipt me-1" aria-hidden="true"></i>Đơn mua
</a>
</li>

<li class="nav-item">

<a class="btn btn-dark"
href="index.php?page=logout">

Đăng xuất

</a>

</li>
<?php if(isset($_SESSION['user']['role_id']) && $_SESSION['user']['role_id']=='1'): ?>

<li class="nav-item ms-2">

<a href="index.php?page=admin_dashboard"
class="btn btn-warning">

⚙ Admin

</a>

</li>

<?php endif; ?>
<?php else: ?>

<li class="nav-item me-2">

<a class="btn btn-outline-light"
href="index.php?page=register">

Đăng ký

</a>

</li>

<li class="nav-item">

<a class="btn btn-light"
href="index.php?page=login">

Đăng nhập

</a>

</li>

<?php endif; ?>

</ul>

</div>
</div>
</nav>

<main class="container site-main flex-grow-1 mt-4">