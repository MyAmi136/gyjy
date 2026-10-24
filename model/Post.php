<?php
require_once "Database.php";

class Post{

private $conn;

public function __construct(){
$this->conn = Database::connect();
}

public function getAll(){
return $this->conn
->query("SELECT * FROM posts ORDER BY created_at DESC")
->fetchAll(PDO::FETCH_ASSOC);
}

public function getById($id){

$stmt=$this->conn->prepare(
"SELECT * FROM posts WHERE post_id=?"
);

$stmt->execute([$id]);
return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function create($title, $content, $authorId, $imagePath = null){
    $stmt = $this->conn->prepare(
        "INSERT INTO posts (title, content, image_url, author_id) VALUES (?, ?, ?, ?)"
    );

    return $stmt->execute([$title, $content, $imagePath, $authorId]);
}

public function update($id, $title, $content, $imagePath = null){
    $stmt = $this->conn->prepare(
        "UPDATE posts SET title = ?, content = ?, image_url = COALESCE(?, image_url) WHERE post_id = ?"
    );

    return $stmt->execute([$title, $content, $imagePath, $id]);
}

public function delete($id){
    $stmt = $this->conn->prepare("DELETE FROM posts WHERE post_id = ?");
    return $stmt->execute([$id]);
}
}