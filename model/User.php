<?php
require_once 'Database.php';

class User {

    private $conn;

    public function __construct(){
        $this->conn = Database::connect();
    }

    // LOGIN
    public function login($email,$password){

        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user && $password == $user['password_hash']){
            return $user;
        }

        return false;
    }
public function getAll(){
    $stmt = $this->conn->query("SELECT * FROM users");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    // REGISTER
    public function register($data){

        $sql = "INSERT INTO users
        (username,email,password_hash,full_name,role_id,is_active)
        VALUES (?,?,?,?,2,1)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $data['username'],
            $data['email'],
            $data['password'],
            $data['full_name']
        ]);
    }
}