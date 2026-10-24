<?php

require_once "model/Database.php";
require_once "model/Order.php";
require_once "model/Product.php";
require_once "model/Post.php";
require_once "model/User.php";
require_once "model/ImageUpload.php";
class AdminController {

    // ===============================
    // CHECK ADMIN
    // ===============================
    private function checkAdmin(){

        if(!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != '1'){
            die("Bạn không có quyền truy cập");
        }
    }

    // ===============================
    // DASHBOARD (THỐNG KÊ)
    // ===============================
    public function dashboard(){

        $this->checkAdmin();

        $database = new Database();
        $db = $database->connect();

        $query = "
        SELECT
        COUNT(*) as total_orders,
        COALESCE(SUM(total_amount), 0) as revenue
        FROM orders
        WHERE status <> 'cancelled'
        ";

        $stmt = $db->prepare($query);
        $stmt->execute();

        $stats = $stmt->fetch(PDO::FETCH_ASSOC);

        require "view/admin/dashboard.php";
    }

    // ===============================
    // DANH SÁCH ĐƠN HÀNG
    // ===============================
    public function orders(){

        $this->checkAdmin();

        $database = new Database();
        $db = $database->connect();

        $order = new Order($db);

        $orders = $order->getAll();

        require "view/admin/orders.php";
    }

    // ===============================
    // CHI TIẾT ĐƠN HÀNG
    // ===============================
    public function orderDetail(){

        $this->checkAdmin();

        $id = $_GET['id'];

        $database = new Database();
        $db = $database->connect();

        $order = new Order($db);

        $orderItems = $order->getOrderItems($id);

        require "view/admin/order_detail.php";
    }

    // ===============================
    // CẬP NHẬT TRẠNG THÁI ĐƠN
    // ===============================
    public function updateStatus(){

        $this->checkAdmin();

        $order_id = $_POST['order_id'];
        $status = $_POST['status'];

        $order = new Order();

        $order->updateStatus($order_id, $status);

        header("Location: index.php?page=admin_orders");
    }

    // ===============================
    // TẠO SẢN PHẨM
    // ===============================
    public function createProduct(){

        $this->checkAdmin();

        require "view/admin/product_create.php";
    }

    // ===============================
    // LƯU SẢN PHẨM
    // ===============================
    public function saveProduct(){

        $this->checkAdmin();

        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            header("Location:index.php?page=admin_create_product");
            exit;
        }

        $productName = trim($_POST['product_name'] ?? '');
        $price = filter_var($_POST['price'] ?? null, FILTER_VALIDATE_FLOAT);
        if($productName === '' || $price === false || $price < 0){
            $_SESSION['error'] = "Vui lòng nhập tên sản phẩm và giá hợp lệ.";
            header("Location:index.php?page=admin_create_product");
            exit;
        }

        try {
            $imagePath = ImageUpload::save(
                $_FILES['featured_image'] ?? null,
                __DIR__ . '/../img/uploads',
                'img/uploads'
            );
        } catch (RuntimeException $exception) {
            $_SESSION['error'] = $exception->getMessage();
            header("Location:index.php?page=admin_create_product");
            exit;
        }

        $database = new Database();
        $db = $database->connect();

        $product = new Product($db);

        $product->insert($_POST, $imagePath);

        $_SESSION['success'] = "Đã thêm sản phẩm.";
        header("Location:index.php?page=admin_products");
        exit;
    }
public function products(){

$this->checkAdmin();

$db = (new Database())->connect();

$product = new Product($db);

$products = $product->getAll();

require "view/admin/products.php";
}
public function editProduct(){

$this->checkAdmin();

$id = $_GET['id'];

$db = (new Database())->connect();

$product = new Product($db);

$data = $product->getById($id);

require "view/admin/product_edit.php";
}

// ===============================
// CẬP NHẬT SẢN PHẨM
// ===============================
public function updateProduct(){

    $this->checkAdmin();

    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        header("Location:index.php?page=admin_products");
        exit;
    }

    $productId = filter_var($_POST['product_id'] ?? null, FILTER_VALIDATE_INT);
    $productName = trim($_POST['product_name'] ?? '');
    $price = filter_var($_POST['price'] ?? null, FILTER_VALIDATE_FLOAT);
    if($productId === false || $productId < 1 || $productName === '' || $price === false || $price < 0){
        $_SESSION['error'] = "Thông tin sản phẩm không hợp lệ.";
        header("Location:index.php?page=admin_products");
        exit;
    }

    try {
        $imagePath = ImageUpload::save(
            $_FILES['featured_image'] ?? null,
            __DIR__ . '/../img/uploads',
            'img/uploads'
        );
    } catch (RuntimeException $exception) {
        $_SESSION['error'] = $exception->getMessage();
        header("Location:index.php?page=admin_edit_product&id=" . $productId);
        exit;
    }

    $database = new Database();
    $db = $database->connect();

    $product = new Product($db);

    $product->update($_POST, $imagePath);

    $_SESSION['success'] = "Đã cập nhật sản phẩm.";
    header("Location:index.php?page=admin_products");
    exit;
}

public function deleteProduct(){

$this->checkAdmin();

$id = $_GET['id'];

$db = (new Database())->connect();

$product = new Product($db);

$product->delete($id);

header("Location:index.php?page=admin_products&msg=deleted");
}
public function posts(){

$this->checkAdmin();

$post = new Post();

$posts = $post->getAll();

require "view/admin/posts.php";
}

public function editPost(){

    $this->checkAdmin();

    $postId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    if($postId === false || $postId < 1){
        $_SESSION['error'] = "Bài viết không hợp lệ.";
        header("Location:index.php?page=admin_posts");
        exit;
    }

    $post = (new Post())->getById($postId);
    if(!$post){
        $_SESSION['error'] = "Không tìm thấy bài viết.";
        header("Location:index.php?page=admin_posts");
        exit;
    }

    require "view/admin/post_edit.php";
}

public function updatePost(){

    $this->checkAdmin();

    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        header("Location:index.php?page=admin_posts");
        exit;
    }

    $postId = filter_var($_POST['post_id'] ?? null, FILTER_VALIDATE_INT);
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if($postId === false || $postId < 1 || $title === '' || $content === ''){
        $_SESSION['error'] = "Vui lòng nhập tiêu đề và nội dung bài viết hợp lệ.";
        header("Location:index.php?page=admin_posts");
        exit;
    }

    $postModel = new Post();
    if(!$postModel->getById($postId)){
        $_SESSION['error'] = "Không tìm thấy bài viết.";
        header("Location:index.php?page=admin_posts");
        exit;
    }

    try {
        $imagePath = ImageUpload::save(
            $_FILES['post_image'] ?? null,
            __DIR__ . '/../img/uploads',
            'img/uploads'
        );
    } catch (RuntimeException $exception) {
        $_SESSION['error'] = $exception->getMessage();
        header("Location:index.php?page=admin_edit_post&id=" . $postId);
        exit;
    }

    if(!$postModel->update($postId, $title, $content, $imagePath)){
        $_SESSION['error'] = "Không thể cập nhật bài viết. Vui lòng thử lại.";
        header("Location:index.php?page=admin_edit_post&id=" . $postId);
        exit;
    }

    $_SESSION['success'] = "Đã cập nhật bài viết.";
    header("Location:index.php?page=admin_posts");
    exit;
}

public function savePost(){

    $this->checkAdmin();

    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        header("Location:index.php?page=admin_posts");
        exit;
    }

    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if($title === '' || $content === ''){
        $_SESSION['error'] = "Vui lòng nhập tiêu đề và nội dung bài viết.";
        header("Location:index.php?page=admin_posts");
        exit;
    }

    try {
        $imagePath = ImageUpload::save(
            $_FILES['post_image'] ?? null,
            __DIR__ . '/../img/uploads',
            'img/uploads'
        );
    } catch (RuntimeException $exception) {
        $_SESSION['error'] = $exception->getMessage();
        header("Location:index.php?page=admin_posts");
        exit;
    }

    $post = new Post();
    if(!$post->create($title, $content, $_SESSION['user']['user_id'], $imagePath)){
        $_SESSION['error'] = "Không thể đăng bài viết. Vui lòng thử lại.";
        header("Location:index.php?page=admin_posts");
        exit;
    }

    $_SESSION['success'] = "Đã đăng bài viết.";
    header("Location:index.php?page=admin_posts");
    exit;
}

public function deletePost(){

    $this->checkAdmin();

    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        header("Location:index.php?page=admin_posts");
        exit;
    }

    $postId = filter_var($_POST['post_id'] ?? null, FILTER_VALIDATE_INT);
    if($postId === false || $postId < 1){
        $_SESSION['error'] = "Mã bài viết không hợp lệ.";
        header("Location:index.php?page=admin_posts");
        exit;
    }

    $post = new Post();
    if(!$post->delete($postId)){
        $_SESSION['error'] = "Không thể xóa bài viết. Vui lòng thử lại.";
        header("Location:index.php?page=admin_posts");
        exit;
    }

    $_SESSION['success'] = "Đã xóa bài viết.";
    header("Location:index.php?page=admin_posts");
    exit;
}

public function users(){

$this->checkAdmin();

$db = (new Database())->connect();

$user = new User($db);

$users = $user->getAll();

require "view/admin/users.php";
}

// ===============================
// THỐNG KÊ
// ===============================
public function statistics(){

    $this->checkAdmin();

    $database = new Database();
    $db = $database->connect();

    $periodType = $_GET['period'] ?? 'month';
    $allowedPeriods = ['day', 'week', 'month', 'quarter', 'year'];
    if (!in_array($periodType, $allowedPeriods, true)) {
        $periodType = 'month';
    }

    $today = new DateTimeImmutable('today');
    $selectedDate = $today->format('Y-m-d');
    $selectedMonth = $today->format('Y-m');
    $selectedYear = (int) $today->format('Y');
    $selectedQuarter = (int) ceil((int) $today->format('n') / 3);

    $dateInput = $_GET['period_date'] ?? '';
    if (is_string($dateInput)) {
        $parsedDate = DateTimeImmutable::createFromFormat('!Y-m-d', $dateInput);
        if ($parsedDate && $parsedDate->format('Y-m-d') === $dateInput) {
            $selectedDate = $dateInput;
        }
    }

    $monthInput = $_GET['period_month'] ?? '';
    if (is_string($monthInput)) {
        $parsedMonth = DateTimeImmutable::createFromFormat('!Y-m', $monthInput);
        if ($parsedMonth && $parsedMonth->format('Y-m') === $monthInput) {
            $selectedMonth = $monthInput;
        }
    }

    $yearInput = filter_var($_GET['period_year'] ?? null, FILTER_VALIDATE_INT);
    if ($yearInput !== false && $yearInput !== null && $yearInput >= 2000 && $yearInput <= (int) $today->format('Y') + 1) {
        $selectedYear = $yearInput;
    }

    $quarterInput = filter_var($_GET['period_quarter'] ?? null, FILTER_VALIDATE_INT);
    if ($quarterInput !== false && $quarterInput !== null && $quarterInput >= 1 && $quarterInput <= 4) {
        $selectedQuarter = $quarterInput;
    }

    switch ($periodType) {
        case 'day':
            $startDate = new DateTimeImmutable($selectedDate);
            $periodLabel = 'Ngày ' . $startDate->format('d/m/Y');
            $groupBy = 'DATE(o.created_at)';
            $periodExpression = 'DATE(o.created_at)';
            break;
        case 'week':
            $startDate = (new DateTimeImmutable($selectedDate))->modify('monday this week');
            $periodLabel = 'Tuần ' . $startDate->format('d/m/Y') . ' - ' . $startDate->modify('+6 days')->format('d/m/Y');
            $groupBy = 'DATE(o.created_at)';
            $periodExpression = 'DATE(o.created_at)';
            break;
        case 'quarter':
            $startMonth = (($selectedQuarter - 1) * 3) + 1;
            $startDate = new DateTimeImmutable(sprintf('%04d-%02d-01', $selectedYear, $startMonth));
            $periodLabel = $selectedQuarter . ' - ' . $selectedYear;
            $periodExpression = "DATE_FORMAT(o.created_at, '%Y-%m')";
            $groupBy = $periodExpression;
            break;
        case 'year':
            $startDate = new DateTimeImmutable(sprintf('%04d-01-01', $selectedYear));
            $periodLabel = 'Năm ' . $selectedYear;
            $periodExpression = "DATE_FORMAT(o.created_at, '%Y-%m')";
            $groupBy = $periodExpression;
            break;
        case 'month':
        default:
            $startDate = new DateTimeImmutable($selectedMonth . '-01');
            $periodLabel = 'Tháng ' . $startDate->format('m/Y');
            $groupBy = 'DATE(o.created_at)';
            $periodExpression = 'DATE(o.created_at)';
            break;
    }

    $endDate = $periodType === 'week'
        ? $startDate->modify('+7 days')
        : $startDate->modify($periodType === 'day' ? '+1 day' : ($periodType === 'month' ? '+1 month' : ($periodType === 'quarter' ? '+3 months' : '+1 year')));
    $startDateValue = $startDate->format('Y-m-d 00:00:00');
    $endDateValue = $endDate->format('Y-m-d 00:00:00');
    $salesDate = "COALESCE(
        (SELECT pay.paid_at
         FROM payments pay
         WHERE pay.order_id = o.order_id AND pay.payment_status = 'paid'
         ORDER BY pay.payment_id DESC
         LIMIT 1),
        o.created_at
    )";

    $periodExpression = str_replace('o.created_at', $salesDate, $periodExpression);
    $groupBy = str_replace('o.created_at', $salesDate, $groupBy);

    $itemTotals = "
        SELECT order_id, SUM(quantity) AS quantity_sold
        FROM order_items
        GROUP BY order_id
    ";
    $dateCondition = "o.status <> 'cancelled' AND $salesDate >= ? AND $salesDate < ?";

    $sql = "
        SELECT
            COUNT(o.order_id) AS total_orders,
            COALESCE(SUM(items.quantity_sold), 0) AS total_products_sold,
            COALESCE(SUM(o.total_amount), 0) AS total_revenue,
            COALESCE(AVG(o.total_amount), 0) AS avg_order_value
        FROM orders o
        LEFT JOIN ($itemTotals) items ON items.order_id = o.order_id
        WHERE $dateCondition
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute([$startDateValue, $endDateValue]);
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql = "
        SELECT
            $periodExpression AS period,
            COUNT(o.order_id) AS orders,
            COALESCE(SUM(items.quantity_sold), 0) AS products_sold,
            COALESCE(SUM(o.total_amount), 0) AS revenue
        FROM orders o
        LEFT JOIN ($itemTotals) items ON items.order_id = o.order_id
        WHERE $dateCondition
        GROUP BY $groupBy
        ORDER BY period DESC
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute([$startDateValue, $endDateValue]);
    $periodStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = "
        SELECT
            p.product_id,
            p.product_name,
            SUM(oi.quantity) AS total_sold,
            SUM(oi.quantity * oi.price) AS revenue
        FROM order_items oi
        JOIN orders o ON o.order_id = oi.order_id
        JOIN products p ON p.product_id = oi.product_id
        WHERE $dateCondition
        GROUP BY p.product_id, p.product_name
        ORDER BY total_sold DESC
        LIMIT 10
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute([$startDateValue, $endDateValue]);
    $top_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    require "view/admin/statistics.php";
}
}