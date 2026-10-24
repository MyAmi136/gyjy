<?php
require_once 'Database.php';

class Payment {

    private $conn;
    private $table = "payments";

    public function __construct(){
        $this->conn = Database::connect();
    }

    // Create payment record
    public function create($data){
        
        $sql = "INSERT INTO {$this->table} 
                (order_id, payment_method, payment_status, transaction_code) 
                VALUES (?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        
        return $stmt->execute([
            $data['order_id'],
            $data['payment_method'] ?? 'cod',
            $data['payment_status'] ?? 'pending',
            $data['transaction_code'] ?? null
        ]);
    }

    // Get payment by order ID
    public function getByOrderId($order_id){
        
        $sql = "SELECT * FROM {$this->table} WHERE order_id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$order_id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update payment status
    public function updateStatus($payment_id, $status, $transaction_code = null){
        
        if($transaction_code){
            $sql = "UPDATE {$this->table} 
                    SET payment_status = ?, transaction_code = ?, paid_at = NOW() 
                    WHERE payment_id = ?";
            
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$status, $transaction_code, $payment_id]);
        } else {
            $sql = "UPDATE {$this->table} 
                    SET payment_status = ?, paid_at = NOW() 
                    WHERE payment_id = ?";
            
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$status, $payment_id]);
        }
    }

    // Get all payments
    public function getAll(){
        
        $sql = "SELECT p.*, o.total_amount 
                FROM {$this->table} p
                JOIN orders o ON p.order_id = o.order_id
                ORDER BY p.payment_id DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>
