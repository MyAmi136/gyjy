ALTER TABLE payments
    ADD COLUMN payos_order_code BIGINT UNSIGNED NULL AFTER payment_method,
    ADD UNIQUE KEY uq_payments_payos_order_code (payos_order_code);
