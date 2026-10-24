ALTER TABLE payments
    MODIFY payment_method ENUM('cod', 'momo', 'vnpay', 'paypal', 'stripe', 'payos') DEFAULT NULL;

ALTER TABLE orders
    MODIFY status ENUM('pending', 'processing', 'shipping', 'completed', 'cancelled', 'paid') DEFAULT 'pending';
