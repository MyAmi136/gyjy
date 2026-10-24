<?php
require_once 'model/User.php';

class AuthController {

    // LOGIN
    public function login(){

        if($_SERVER['REQUEST_METHOD']=='POST'){

            $model = new User();

            $user = $model->login(
                $_POST['email'],
                $_POST['password']
            );

            if($user){

$_SESSION['user'] = [
"user_id"=>$user['user_id'],
"full_name"=>$user['full_name'],
"email"=>$user['email'],
"role_id"=>$user['role_id']
];

            $redirect = $_POST['redirect'] ?? '';
            $destination = in_array($redirect, ['checkout', 'my_orders'], true)
                ? "index.php?page=" . $redirect
                : "index.php";
            header("Location: " . $destination);
exit;
}

            $error="Sai tài khoản hoặc mật khẩu";
        }

        require 'view/login.php';
    }

    // REGISTER
    public function register(){

        if($_SERVER['REQUEST_METHOD']=='POST'){

            $model = new User();

            $model->register($_POST);

            // auto login
            $user = $model->login(
                $_POST['email'],
                $_POST['password']
            );

            $_SESSION['user'] = [
"user_id"=>$user['user_id'],
"full_name"=>$user['full_name'],
"email"=>$user['email'],
"role_id"=>$user['role_id']
];

            $redirect = $_POST['redirect'] ?? '';
            $destination = in_array($redirect, ['checkout', 'my_orders'], true)
                ? "index.php?page=" . $redirect
                : "index.php";
            header("Location: " . $destination);
            exit;
        }

        require 'view/register.php';
    }

    // LOGOUT
    public function logout(){
        session_destroy();
        header("Location: index.php");
    }
}