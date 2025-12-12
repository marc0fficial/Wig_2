<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}

// Validate required fields
$requiredFields = ['name', 'phone', 'email', 'address', 'payment_method'];
foreach ($requiredFields as $field) {
    if (!isset($input[$field]) || empty(trim($input[$field]))) {
        echo json_encode(['success' => false, 'message' => 'Missing required field: ' . $field]);
        exit();
    }
}

// Get cart items
$cartItems = getCartItems();
if (count($cartItems) === 0) {
    echo json_encode(['success' => false, 'message' => 'Cart is empty']);
    exit();
}

// Prepare customer data
$customerData = [
    'name' => sanitize($input['name']),
    'phone' => sanitize($input['phone']),
    'email' => sanitize($input['email']),
    'address' => sanitize($input['address'])
];

// Add notes if provided
if (isset($input['notes']) && !empty(trim($input['notes']))) {
    $customerData['address'] .= "\n\nNotes: " . sanitize($input['notes']);
}

$paymentMethod = sanitize($input['payment_method']);

// Validate payment method
if (!in_array($paymentMethod, ['airtel_money', 'card'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid payment method']);
    exit();
}

// Create order
$orderId = createOrder($customerData, $cartItems, $paymentMethod);

if ($orderId) {
    // Create payment record
    global $conn;
    $cartTotal = getCartTotal();
    $shippingCost = $cartTotal >= FREE_SHIPPING_THRESHOLD ? 0 : SHIPPING_COST;
    $finalTotal = $cartTotal + $shippingCost;
    
    $stmt = $conn->prepare("INSERT INTO payments (order_id, payment_method, amount, status) VALUES (?, ?, ?, 'pending')");
    $stmt->bind_param("isd", $orderId, $paymentMethod, $finalTotal);
    $stmt->execute();
    
    echo json_encode([
        'success' => true,
        'message' => 'Order created successfully',
        'order_id' => $orderId
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to create order']);
}
?>
