<?php

class Cart {

    public static function add($id){

        if(!isset($_SESSION['cart'])){
            $_SESSION['cart'] = [];
        }

        if(isset($_SESSION['cart'][$id])){
            $_SESSION['cart'][$id]++;
        }else{
            $_SESSION['cart'][$id] = 1;
        }
    }

    public static function update($id,$qty){
        $_SESSION['cart'][$id] = $qty;
    }

    public static function remove($id){
        unset($_SESSION['cart'][$id]);
    }

    public static function clear(){
        unset($_SESSION['cart']);
    }

}