<?php
session_start();

/*
|--------------------------------------------------------------------------
| Router chính của Website
|--------------------------------------------------------------------------
| Điều hướng request tới controller tương ứng
|
| Ví dụ:
| index.php?page=home
| index.php?page=product_detail&id=1
|
*/

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch($page){

/* ======================================================
   TRANG CHỦ
====================================================== */
case "home":
    require_once "controller/HomeController.php";
    $controller = new HomeController();
    $controller->index();
break;


/* ======================================================
   SẢN PHẨM
====================================================== */
case "category":

require_once "controller/CategoryController.php";

$controller = new CategoryController();

$controller->show();

break;
case "products":
    require_once "controller/ProductController.php";
    $controller = new ProductController();
    $controller->index();
break;

case "product_detail":
    require_once "controller/ProductController.php";
    $controller = new ProductController();
    $controller->detail($_GET['id'] ?? null);
break;


/* ======================================================
   GIỎ HÀNG (CART)
====================================================== */

case "add-cart":
    require_once "controller/CartController.php";
    $controller = new CartController();
    $controller->add();
break;

case "cart":
    require_once "controller/CartController.php";
    $controller = new CartController();
    $controller->view();
break;

case "update-cart":
    require_once "controller/CartController.php";
    $controller = new CartController();
    $controller->update();
break;

case "remove-cart":
    require_once "controller/CartController.php";
    $controller = new CartController();
    $controller->remove();
break;

case "clear-cart":
    require_once "controller/CartController.php";
    $controller = new CartController();
    $controller->clear();
break;
case "increase-cart":
require_once "controller/CartController.php";
(new CartController())->increase();
break;

case "decrease-cart":
require_once "controller/CartController.php";
(new CartController())->decrease();
break;

/* ======================================================
   AUTH (LOGIN / REGISTER / LOGOUT)
====================================================== */

case "login":
    require_once "controller/AuthController.php";
    $controller = new AuthController();
    $controller->login();
break;

case "register":
    require_once "controller/AuthController.php";
    $controller = new AuthController();
    $controller->register();
break;

case "logout":
    require_once "controller/AuthController.php";
    $controller = new AuthController();
    $controller->logout();
break;


/* ======================================================
   BÀI VIẾT / BLOG
====================================================== */

case "posts":
    require_once "controller/PostController.php";
    $controller = new PostController();
    $controller->index();
break;

case "post_detail":
    require_once "controller/PostController.php";
    $controller = new PostController();
    $controller->detail();
break;


/* ======================================================
   CHECKOUT / ORDER
====================================================== */

case "checkout":
require_once "controller/OrderController.php";
(new OrderController())->checkout();
break;

case "place-order":
require_once "controller/OrderController.php";
(new OrderController())->placeOrder();
break;

case "order-success":
require "view/order_success.php";
break;

case "mock_payment":
require_once "controller/OrderController.php";
(new OrderController())->mockPayment();
break;

case "mock_payment_process":
require_once "controller/OrderController.php";
(new OrderController())->mockPaymentProcess();
break;

case "vnpay_return":
require_once "controller/OrderController.php";
(new OrderController())->vnpayReturn();
break;
/* ======================================================
   ADMIN DASHBOARD
====================================================== */

case "admin_dashboard":
require "controller/AdminController.php";
(new AdminController())->dashboard();
break;

case "admin_statistics":
require "controller/AdminController.php";
(new AdminController())->statistics();
break;

case "admin_orders":
require "controller/AdminController.php";
(new AdminController())->orders();
break;

case "admin_posts":
require "controller/AdminController.php";
(new AdminController())->posts();
break;

case "admin_save_post":
require "controller/AdminController.php";
(new AdminController())->savePost();
break;

case "admin_edit_post":
require "controller/AdminController.php";
(new AdminController())->editPost();
break;

case "admin_update_post":
require "controller/AdminController.php";
(new AdminController())->updatePost();
break;

case "admin_delete_post":
require "controller/AdminController.php";
(new AdminController())->deletePost();
break;

case "my_orders":
require "controller/OrderController.php";
(new OrderController())->myOrders();
break;

case "my_order_detail":
require "controller/OrderController.php";
(new OrderController())->myOrderDetail();
break;

case "admin_order_detail":
require "controller/AdminController.php";
(new AdminController())->orderDetail();
break;

case "admin_update_status":
require "controller/AdminController.php";
(new AdminController())->updateStatus();
break;

case "admin_products":
require "controller/AdminController.php";
(new AdminController())->products();
break;

case "admin_create_product":
require "controller/AdminController.php";
(new AdminController())->createProduct();
break;

case "admin_save_product":
require "controller/AdminController.php";
(new AdminController())->saveProduct();
break;

case "admin_edit_product":
require "controller/AdminController.php";
(new AdminController())->editProduct();
break;

case "admin_update_product":
require "controller/AdminController.php";
(new AdminController())->updateProduct();
break;

case "admin_delete_product":
require "controller/AdminController.php";
(new AdminController())->deleteProduct();
break;

/* ======================================================
   DEFAULT PAGE
====================================================== */

default:
    require_once "controller/HomeController.php";
    $controller = new HomeController();
    $controller->index();
break;

}