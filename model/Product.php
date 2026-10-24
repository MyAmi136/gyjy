<?php
require_once 'Database.php';

class Product {

    private $conn;
    private $table = "products";

    public function __construct(){
        $this->conn = Database::connect();
    }

    // lấy tất cả sản phẩm
    public function getAll(){

$sql="SELECT * FROM products WHERE status=1";

$stmt=$this->conn->prepare($sql);
$stmt->execute();

return $stmt->fetchAll(PDO::FETCH_ASSOC);

}

    // lấy 1 sản phẩm theo id
    public function getById($id){

        $query = "SELECT p.*, c.category_name
                  FROM {$this->table} p
                  LEFT JOIN categories c ON c.category_id = p.category_id
                  WHERE p.product_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getBestSeller(){

$sql="
SELECT
    p.product_id,
    p.product_name,
    p.description,
    p.price,
    p.stock,
    p.category_id,
    p.brand_id,
    p.featured_image_url,
    p.created_at,
    p.status,
    COALESCE(MAX(oi.total_sold), 0) as total_sold
FROM products p
LEFT JOIN (
    SELECT oi.product_id, SUM(oi.quantity) AS total_sold
    FROM order_items oi
    JOIN orders o ON o.order_id = oi.order_id
    WHERE o.status <> 'cancelled'
    GROUP BY oi.product_id
) oi ON p.product_id = oi.product_id
WHERE p.status = 1
GROUP BY p.product_id
ORDER BY total_sold DESC, p.is_best_seller DESC, p.product_id DESC
LIMIT 6
";

$stmt = $this->conn->prepare($sql);
$stmt->execute();

return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getByCategory($category_id){

$sql="SELECT * FROM products WHERE category_id=?";

$stmt=$this->conn->prepare($sql);
$stmt->execute([$category_id]);

return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function insert($data, $imagePath = null){

$sql="INSERT INTO products
(product_name, price, description, category_id, featured_image_url)
VALUES (?,?,?,?,?)";

$stmt=$this->conn->prepare($sql);

$stmt->execute([
$data['product_name'],
$data['price'],
$data['description'],
$data['category_id'] ?? null,
$imagePath
]);

return $stmt->rowCount() > 0;

}

// Cập nhật sản phẩm
public function update($data, $imagePath = null){

    $sql="UPDATE products SET
    product_name=?,
    price=?,
    description=?,
    category_id=?,
    featured_image_url=COALESCE(?, featured_image_url)
    WHERE product_id=?";

    $stmt=$this->conn->prepare($sql);

    return $stmt->execute([
        $data['product_name'],
        $data['price'],
        $data['description'],
        $data['category_id'] ?? null,
        $imagePath,
        $data['product_id']
    ]);
}

public function delete($id){

$sql="UPDATE products SET status=0 WHERE product_id=?";

$stmt=$this->conn->prepare($sql);

return $stmt->execute([$id]);
}

// ✅ Decrease stock when order is placed
public function decreaseStock($product_id, $quantity){

    $sql = "UPDATE products SET stock = stock - ? WHERE product_id = ? AND stock >= ?";

    $stmt = $this->conn->prepare($sql);

    return $stmt->execute([$quantity, $product_id, $quantity]);
}

// ✅ Increase stock if order is cancelled
public function increaseStock($product_id, $quantity){

    $sql = "UPDATE products SET stock = stock + ? WHERE product_id = ?";

    $stmt = $this->conn->prepare($sql);

    return $stmt->execute([$quantity, $product_id]);
}

// ✅ Get all images for a product
public function getAllImages($product_id){

    $sql = "SELECT * FROM product_images WHERE product_id = ? ORDER BY image_id ASC";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$product_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}