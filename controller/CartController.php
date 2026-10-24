<?php
require_once "model/Product.php";

class CartController {

    public function add(){

        // Accept ID from GET or POST
        $id = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : null);
        
        if(!$id){
            error_log("CartController::add() - Product ID not found!");
            $_SESSION['error'] = "Product ID not found";
            header("Location:index.php?page=cart");
            exit;
        }

        $productModel = new Product();
        $product = $productModel->getById($id);

        if(!$product){
            error_log("CartController::add() - Product not found: " . $id);
            $_SESSION['error'] = "Sản phẩm không tồn tại";
            header("Location:index.php?page=cart");
            exit;
        }

        // Check stock
        if(isset($product['stock']) && $product['stock'] <= 0){
            error_log("CartController::add() - Out of stock: " . $id);
            $_SESSION['error'] = "Sản phẩm đã hết hàng";
            header("Location:index.php?page=cart");
            exit;
        }

        if(!isset($_SESSION['cart'])){
            $_SESSION['cart'] = [];
        }

        // Get quantity from POST or default to 1
        $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : (isset($_GET['qty']) ? (int)$_GET['qty'] : 1);
        
        // Validate quantity
        if($qty < 1) $qty = 1;
        if(isset($product['stock']) && $qty > $product['stock']){
            $qty = $product['stock'];
        }

        if(isset($_SESSION['cart'][$id])){

            $_SESSION['cart'][$id]['qty'] += $qty;

        }else{

            // Get product image
            $image_url = $this->getProductImage($id);

            $_SESSION['cart'][$id] = [

                "name"=>$product['product_name'],
                "price"=>$product['price'],
                "image_url"=>$image_url,
                "qty"=>$qty

            ];

        }

        error_log("CartController::add() - Added product: " . $id . " qty: " . $qty);
        $_SESSION['success'] = "Đã thêm " . $product['product_name'] . " vào giỏ hàng";
        
        $action = $_POST['action'] ?? $_GET['action'] ?? 'add';
        if($action === 'buy_now'){
            header("Location:index.php?page=checkout");
            exit;
        }

        header("Location:index.php?page=cart");
        exit;
    }

    public function view(){
        require "view/cart.php";
    }

    public function clear(){
        unset($_SESSION['cart']);
        $_SESSION['success'] = "Đã xóa toàn bộ giỏ hàng";
        header("Location:index.php?page=cart");
        exit;
    }

    public function remove(){

        $id = $_GET['id'];

        unset($_SESSION['cart'][$id]);

        header("Location:index.php?page=cart");
    }

    public function update(){

        foreach($_POST['qty'] as $id=>$qty){

            $_SESSION['cart'][$id]['qty'] = $qty;
        }

        header("Location:index.php?page=cart");
    }
public function increase(){

$id=$_GET['id'];

$_SESSION['cart'][$id]['qty']++;

header("Location:index.php?page=cart");

}

public function decrease(){

$id=$_GET['id'];

if($_SESSION['cart'][$id]['qty']>1){
$_SESSION['cart'][$id]['qty']--;
}

header("Location:index.php?page=cart");

}
public function checkout(){

    if(!isset($_SESSION['user'])){
        header("Location:index.php?page=login&redirect=checkout");
        exit;
    }

    header("Location:index.php?page=checkout");
}

// ==================
// Helper functions
// ==================
private function getProductImage($product_id){

    $productModel = new Product();
    
    // For now, return a default image
    // In future, fetch from product_images table
    return "default.jpg";
}
}