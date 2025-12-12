<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/config.php';

// Get all products
function getAllProducts($limit = null, $featured = false) {
    global $conn;
    
    $sql = "SELECT * FROM products WHERE stock > 0";
    if ($featured) {
        $sql .= " AND featured = 1";
    }
    $sql .= " ORDER BY created_at DESC";
    if ($limit) {
        $sql .= " LIMIT " . intval($limit);
    }
    
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get product by ID
function getProductById($id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Get products by category
function getProductsByCategory($category) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM products WHERE category = ? AND stock > 0 ORDER BY created_at DESC");
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Search products
function searchProducts($query) {
    global $conn;
    
    $searchTerm = "%{$query}%";
    $stmt = $conn->prepare("SELECT * FROM products WHERE (name LIKE ? OR description LIKE ? OR category LIKE ?) AND stock > 0 ORDER BY created_at DESC");
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get all categories
function getCategories() {
    global $conn;
    
    $result = $conn->query("SELECT DISTINCT category FROM products WHERE stock > 0 ORDER BY category");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Add to cart
function addToCart($productId, $quantity = 1) {
    $product = getProductById($productId);
    
    if (!$product) {
        return false;
    }
    
    if (!isset($_SESSION[CART_SESSION_KEY])) {
        $_SESSION[CART_SESSION_KEY] = [];
    }
    
    // Check if product already in cart
    if (isset($_SESSION[CART_SESSION_KEY][$productId])) {
        $_SESSION[CART_SESSION_KEY][$productId]['quantity'] += $quantity;
    } else {
        $_SESSION[CART_SESSION_KEY][$productId] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'quantity' => $quantity
        ];
    }
    
    return true;
}

// Update cart item quantity
function updateCartQuantity($productId, $quantity) {
    if (isset($_SESSION[CART_SESSION_KEY][$productId])) {
        if ($quantity <= 0) {
            unset($_SESSION[CART_SESSION_KEY][$productId]);
        } else {
            $_SESSION[CART_SESSION_KEY][$productId]['quantity'] = $quantity;
        }
        return true;
    }
    return false;
}

// Remove from cart
function removeFromCart($productId) {
    if (isset($_SESSION[CART_SESSION_KEY][$productId])) {
        unset($_SESSION[CART_SESSION_KEY][$productId]);
        return true;
    }
    return false;
}

// Clear cart
function clearCart() {
    $_SESSION[CART_SESSION_KEY] = [];
}

// Get cart items
function getCartItems() {
    return isset($_SESSION[CART_SESSION_KEY]) ? $_SESSION[CART_SESSION_KEY] : [];
}

// Create order
function createOrder($customerData, $cartItems, $paymentMethod) {
    global $conn;
    
    $conn->begin_transaction();
    
    try {
        // Calculate total
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        // Add shipping if applicable
        if ($total < FREE_SHIPPING_THRESHOLD) {
            $total += SHIPPING_COST;
        }
        
        // Insert customer if not exists
        $stmt = $conn->prepare("INSERT INTO customers (name, email, phone, address) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $customerData['name'], $customerData['email'], $customerData['phone'], $customerData['address']);
        $stmt->execute();
        $customerId = $conn->insert_id;
        
        // Insert order
        $stmt = $conn->prepare("INSERT INTO orders (customer_id, customer_name, customer_email, customer_phone, shipping_address, total_amount, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssds", $customerId, $customerData['name'], $customerData['email'], $customerData['phone'], $customerData['address'], $total, $paymentMethod);
        $stmt->execute();
        $orderId = $conn->insert_id;
        
        // Insert order items
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($cartItems as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $stmt->bind_param("iisidi", $orderId, $item['id'], $item['name'], $item['quantity'], $item['price'], $subtotal);
            $stmt->execute();
            
            // Update product stock
            $updateStmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
            $updateStmt->bind_param("ii", $item['quantity'], $item['id']);
            $updateStmt->execute();
        }
        
        $conn->commit();
        return $orderId;
        
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Order creation error: " . $e->getMessage());
        return false;
    }
}

// Get order by ID
function getOrderById($orderId) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Get order items
function getOrderItems($orderId) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Update order status
function updateOrderStatus($orderId, $status) {
    global $conn;
    
    $stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $orderId);
    return $stmt->execute();
}

// Update payment status
function updatePaymentStatus($orderId, $status, $transactionId = null) {
    global $conn;
    
    $stmt = $conn->prepare("UPDATE orders SET payment_status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $orderId);
    $result = $stmt->execute();
    
    if ($transactionId) {
        $stmt = $conn->prepare("UPDATE payments SET transaction_id = ?, status = ? WHERE order_id = ?");
        $stmt->bind_param("ssi", $transactionId, $status, $orderId);
        $stmt->execute();
    }
    
    return $result;
}

// Send email notification
function sendEmailNotification($to, $subject, $message) {
    $headers = "From: " . SMTP_FROM_NAME . " <" . SMTP_FROM_EMAIL . ">\r\n";
    $headers .= "Reply-To: " . SMTP_FROM_EMAIL . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    return mail($to, $subject, $message, $headers);
}

// Send order confirmation to customer
function sendOrderConfirmation($orderId) {
    $order = getOrderById($orderId);
    $items = getOrderItems($orderId);
    
    $message = "<html><body>";
    $message .= "<h2>Order Confirmation - Order #" . $orderId . "</h2>";
    $message .= "<p>Dear " . htmlspecialchars($order['customer_name']) . ",</p>";
    $message .= "<p>Thank you for your order! Here are the details:</p>";
    $message .= "<h3>Order Items:</h3><ul>";
    
    foreach ($items as $item) {
        $message .= "<li>" . htmlspecialchars($item['product_name']) . " x " . $item['quantity'] . " - " . formatPrice($item['subtotal']) . "</li>";
    }
    
    $message .= "</ul>";
    $message .= "<p><strong>Total: " . formatPrice($order['total_amount']) . "</strong></p>";
    $message .= "<p><strong>Payment Method: " . htmlspecialchars($order['payment_method']) . "</strong></p>";
    $message .= "<p>We will process your order shortly.</p>";
    $message .= "<p>Best regards,<br>" . SITE_NAME . "</p>";
    $message .= "</body></html>";
    
    return sendEmailNotification($order['customer_email'], "Order Confirmation - " . SITE_NAME, $message);
}

// Send order notification to admin
function sendAdminOrderNotification($orderId) {
    $order = getOrderById($orderId);
    $items = getOrderItems($orderId);
    
    $message = "<html><body>";
    $message .= "<h2>New Order Received - Order #" . $orderId . "</h2>";
    $message .= "<h3>Customer Details:</h3>";
    $message .= "<p>Name: " . htmlspecialchars($order['customer_name']) . "</p>";
    $message .= "<p>Email: " . htmlspecialchars($order['customer_email']) . "</p>";
    $message .= "<p>Phone: " . htmlspecialchars($order['customer_phone']) . "</p>";
    $message .= "<p>Address: " . htmlspecialchars($order['shipping_address']) . "</p>";
    $message .= "<h3>Order Items:</h3><ul>";
    
    foreach ($items as $item) {
        $message .= "<li>" . htmlspecialchars($item['product_name']) . " x " . $item['quantity'] . " - " . formatPrice($item['subtotal']) . "</li>";
    }
    
    $message .= "</ul>";
    $message .= "<p><strong>Total: " . formatPrice($order['total_amount']) . "</strong></p>";
    $message .= "<p><strong>Payment Method: " . htmlspecialchars($order['payment_method']) . "</strong></p>";
    $message .= "<p><strong>Payment Status: " . htmlspecialchars($order['payment_status']) . "</strong></p>";
    $message .= "</body></html>";
    
    return sendEmailNotification(ADMIN_EMAIL, "New Order #" . $orderId . " - " . SITE_NAME, $message);
}
?>
