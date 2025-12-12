<?php
// Application configuration
session_start();

// Site settings
define('SITE_NAME', 'Wig Elegance');
define('SITE_URL', 'http://localhost/Wig');
define('ADMIN_EMAIL', 'marc0urage10@gmail.com');
define('ADMIN_PHONE', '0798974781');

// Currency settings
define('CURRENCY', '$');
define('CURRENCY_CODE', 'USD');

// Stripe API Keys (Replace with your actual keys)
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_your_publishable_key_here');
define('STRIPE_SECRET_KEY', 'sk_test_your_secret_key_here');

// Airtel Money Configuration
define('AIRTEL_MONEY_NUMBER', '0798974781');
define('AIRTEL_MONEY_NAME', 'Marc Courage');

// Email configuration (using PHP mail function)
define('SMTP_FROM_EMAIL', 'noreply@wigelegance.com');
define('SMTP_FROM_NAME', 'Wig Elegance');

// File upload settings
define('UPLOAD_DIR', __DIR__ . '/../assets/images/products/');
define('MAX_FILE_SIZE', 5242880); // 5MB

// Pagination
define('PRODUCTS_PER_PAGE', 12);

// Tax and shipping
define('TAX_RATE', 0.0); // 0% tax
define('SHIPPING_COST', 10.00);
define('FREE_SHIPPING_THRESHOLD', 200.00);

// Session cart key
define('CART_SESSION_KEY', 'shopping_cart');

// Helper function to check if user is logged in as admin
function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// Helper function to redirect
function redirect($url) {
    header("Location: " . $url);
    exit();
}

// Helper function to sanitize input
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Helper function to format price
function formatPrice($price) {
    return CURRENCY . number_format($price, 2);
}

// Helper function to calculate cart total
function getCartTotal() {
    $total = 0;
    if (isset($_SESSION[CART_SESSION_KEY])) {
        foreach ($_SESSION[CART_SESSION_KEY] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }
    return $total;
}

// Helper function to get cart count
function getCartCount() {
    $count = 0;
    if (isset($_SESSION[CART_SESSION_KEY])) {
        foreach ($_SESSION[CART_SESSION_KEY] as $item) {
            $count += $item['quantity'];
        }
    }
    return $count;
}
?>
