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
        
        $sql = "SELECT p.*, o.total_amount
                FROM {$this->table} p
                JOIN orders o ON o.order_id = p.order_id
                WHERE p.order_id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$order_id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function markPayOSOrderPaid($orderId, $amount, $transactionCode)
    {
        $this->conn->beginTransaction();
        try {
            $sql = "SELECT p.payment_id, p.payment_status, p.payment_method, o.total_amount
                    FROM {$this->table} p
                    JOIN orders o ON o.order_id = p.order_id
                    WHERE p.order_id = ?
                    FOR UPDATE";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$orderId]);
            $payment = $stmt->fetch(PDO::FETCH_ASSOC);

            if (
                !$payment ||
                $payment['payment_method'] !== 'payos' ||
                (int) $amount !== (int) round((float) $payment['total_amount'])
            ) {
                throw new RuntimeException('PayOS webhook không khớp với đơn hàng hoặc số tiền.');
            }

            if ($payment['payment_status'] === 'paid') {
                $this->conn->commit();
                return true;
            }

            $stmt = $this->conn->prepare(
                "UPDATE {$this->table}
                 SET payment_status = 'paid', transaction_code = ?, paid_at = NOW()
                 WHERE payment_id = ? AND payment_status <> 'paid'"
            );
            $stmt->execute([$transactionCode, $payment['payment_id']]);

            $stmt = $this->conn->prepare("UPDATE orders SET status = 'paid' WHERE order_id = ?");
            $stmt->execute([$orderId]);

            $stmt = $this->conn->prepare(
                'SELECT product_id, quantity FROM order_items WHERE order_id = ?'
            );
            $stmt->execute([$orderId]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stockStmt = $this->conn->prepare(
                'UPDATE products SET stock = stock - ? WHERE product_id = ? AND stock >= ?'
            );
            foreach ($items as $item) {
                $quantity = (int) $item['quantity'];
                $stockStmt->execute([$quantity, $item['product_id'], $quantity]);
                if ($stockStmt->rowCount() !== 1) {
                    error_log('PayOS payment succeeded but stock update failed for order ' . $orderId . ', product ' . $item['product_id']);
                }
            }

            $this->conn->commit();
            return true;
        } catch (Throwable $exception) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            throw $exception;
        }
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
