<?php
require_once "model/Post.php";

class PostController{

public function index(){

$model = new Post();
$posts = $model->getAll();

require "view/posts.php";
}

public function detail(){

$id=$_GET['id'];

$model=new Post();
$post=$model->getById($id);

require "view/post_detail.php";
}
}