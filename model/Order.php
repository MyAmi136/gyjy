<?php
require_once "Database.php";

class Order{

private $conn;

public function __construct(){

$this->conn = Database::connect();

}
 // =====================
    // TẠO ORDER
    // =====================
public function createOrder($user_id,$total,$address){

    $conn = Database::connect();

    $sql="INSERT INTO orders
    (user_id,total_amount,status,shipping_address,created_at)
    VALUES (?,?, 'pending', ?, NOW())";

    $stmt=$conn->prepare($sql);

    $stmt->execute([
    $user_id,
    $total,
    $address
    ]);

    return $conn->lastInsertId();

}
 // =====================
    // THÊM CHI TIẾT ĐƠN
    // =====================
public function addOrderDetail($order_id,$pid,$qty,$price){

    $sql = "INSERT INTO order_details
    (order_id,product_id,quantity,price)
    VALUES (?,?,?,?)";

    $stmt = $this->conn->prepare($sql);

    $stmt->execute([
    $order_id,
    $pid,
    $qty,
    $price
    ]);

}
 // =====================
    // LẤY TẤT CẢ ORDER
    // =====================
public function getAll(){

$sql = "
SELECT 
o.*,
u.full_name,
u.email
FROM orders o
LEFT JOIN users u ON o.user_id = u.user_id
ORDER BY o.created_at DESC
";

$stmt = $this->conn->prepare($sql);
$stmt->execute();

return $stmt->fetchAll(PDO::FETCH_ASSOC);

}

public function getByCustomer($userId){
    $sql = "
    SELECT o.*, pay.payment_method, pay.payment_status
    FROM orders o
    LEFT JOIN payments pay ON pay.order_id = o.order_id
    WHERE o.user_id = ?
    ORDER BY o.created_at DESC
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$userId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getCustomerOrder($orderId, $userId){
    $sql = "
    SELECT o.*, pay.payment_method, pay.payment_status
    FROM orders o
    LEFT JOIN payments pay ON pay.order_id = o.order_id
    WHERE o.order_id = ? AND o.user_id = ?
    LIMIT 1
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$orderId, $userId]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function getCustomerOrderItems($orderId, $userId){
    $sql = "
    SELECT oi.order_item_id, oi.product_id, oi.quantity, oi.price,
           p.product_name, p.featured_image_url AS image_url
    FROM order_items oi
    INNER JOIN orders o ON o.order_id = oi.order_id
    LEFT JOIN products p ON p.product_id = oi.product_id
    WHERE oi.order_id = ? AND o.user_id = ?
    ORDER BY oi.order_item_id ASC
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$orderId, $userId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

 // =====================
    // ✅ CHI TIẾT ORDER
    // =====================
    public function getOrderItems($order_id){

    $sql = "
    SELECT 
        oi.*,
        p.product_name,
        pi.image_url
    FROM order_items oi
    JOIN products p 
        ON oi.product_id = p.product_id
    LEFT JOIN product_images pi
        ON p.product_id = pi.product_id
    WHERE oi.order_id = ?
    GROUP BY oi.order_item_id
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$order_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // =====================
    // ✅ UPDATE STATUS
    // =====================
    public function updateStatus($order_id,$status){

        $sql = "UPDATE orders SET status=? WHERE order_id=?";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $status,
            $order_id
        ]);
    }
}