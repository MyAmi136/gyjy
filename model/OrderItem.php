<?php
require_once 'Database.php';

class OrderItem{

public function addItem($order_id,$product_id,$quantity,$price){

$conn = Database::connect();

$sql="INSERT INTO order_items
(order_id,product_id,quantity,price)
VALUES(?,?,?,?)";

$stmt=$conn->prepare($sql);

$stmt->execute([
$order_id,
$product_id,
$quantity,
$price
]);

}

}