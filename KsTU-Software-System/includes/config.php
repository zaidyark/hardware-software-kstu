<?php
// Start session at the very beginning
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Initialize tickets array if not exists
if (!isset($_SESSION['tickets'])) {
    $_SESSION['tickets'] = [];
}

// Database configuration (for future use)
define('DB_HOST', 'localhost');
define('DB_NAME', 'college_hardware_support');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application settings
define('APP_NAME', 'College Hardware Support');
define('APP_VERSION', '1.0');

// Function to generate a unique ticket ID
function generateTicketID() {
    return 'TKT-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
}

// Check if user is logged in (for admin pages)
function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}
?>