<?php

require_once "model/Product.php";
require_once "model/Category.php";

class HomeController{

public function index(){

$productModel = new Product();
$categoryModel = new Category();

$products = array_slice($productModel->getAll(), 0, 12);
$bestsellers = $productModel->getBestSeller();
$categories = $categoryModel->getParent();

require "view/home.php";
}
}