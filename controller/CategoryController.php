<?php

require_once "model/Product.php";

class CategoryController{

public function show(){

$id = $_GET['id'];

$productModel = new Product();

$products = $productModel->getByCategory($id);

require_once "view/category_products.php";

}

}