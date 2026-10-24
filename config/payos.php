<?php
/**
 * PAYOS configuration
 *
 * Set these values from your PayOS merchant account using environment variables:
 * PAYOS_CLIENT_ID, PAYOS_API_KEY, PAYOS_CHECKSUM_KEY
 * PAYOS_RETURN_URL and PAYOS_CANCEL_URL must be absolute public URLs.
 * On shared hosting, place secrets in config/payos.local.php (not in Git).
 */

if (file_exists(__DIR__ . '/payos.local.php')) {
    require_once __DIR__ . '/payos.local.php';
}

if (!defined('PAYOS_CLIENT_ID')) {
    define('PAYOS_CLIENT_ID', getenv('PAYOS_CLIENT_ID') ?: '');
}

if (!defined('PAYOS_API_KEY')) {
    define('PAYOS_API_KEY', getenv('PAYOS_API_KEY') ?: '');
}

if (!defined('PAYOS_CHECKSUM_KEY')) {
    define('PAYOS_CHECKSUM_KEY', getenv('PAYOS_CHECKSUM_KEY') ?: '');
}

if (!defined('PAYOS_RETURN_URL')) {
    define('PAYOS_RETURN_URL', getenv('PAYOS_RETURN_URL') ?: '');
}

if (!defined('PAYOS_CANCEL_URL')) {
    define('PAYOS_CANCEL_URL', getenv('PAYOS_CANCEL_URL') ?: '');
}
