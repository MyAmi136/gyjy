<?php
/**
 * PAYOS configuration
 *
 * Fill in the values from your PayOS merchant account.
 * For local testing, you can also set them in environment variables:
 * PAYOS_CLIENT_ID, PAYOS_API_KEY, PAYOS_CHECKSUM_KEY
 */

if (!defined('PAYOS_CLIENT_ID')) {
    define('PAYOS_CLIENT_ID', getenv('PAYOS_CLIENT_ID') ?: 'YOUR_CLIENT_ID');
}

if (!defined('PAYOS_API_KEY')) {
    define('PAYOS_API_KEY', getenv('PAYOS_API_KEY') ?: 'YOUR_API_KEY');
}

if (!defined('PAYOS_CHECKSUM_KEY')) {
    define('PAYOS_CHECKSUM_KEY', getenv('PAYOS_CHECKSUM_KEY') ?: 'YOUR_CHECKSUM_KEY');
}
