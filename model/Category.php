<?php
require_once "Database.php";

class Category{

private $conn;

public function __construct(){
$this->conn = Database::connect();
}

public function getParent(){

$sql="SELECT * FROM categories WHERE parent_id IS NULL";

$stmt=$this->conn->prepare($sql);
$stmt->execute();

return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getChild($parent_id){

$sql="SELECT * FROM categories WHERE parent_id=?";

$stmt=$this->conn->prepare($sql);
$stmt->execute([$parent_id]);

return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function getAll(){

$sql="SELECT * FROM categories";

$stmt=$this->conn->prepare($sql);
$stmt->execute();

return $stmt->fetchAll(PDO::FETCH_ASSOC);

}
}