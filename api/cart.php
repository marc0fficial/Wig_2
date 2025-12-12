<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['action'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}

$action = $input['action'];
$response = ['success' => false];

switch ($action) {
    case 'add':
        if (!isset($input['product_id']) || !isset($input['quantity'])) {
            $response['message'] = 'Missing required parameters';
            break;
        }
        
        $productId = intval($input['product_id']);
        $quantity = intval($input['quantity']);
        
        if ($quantity <= 0) {
            $response['message'] = 'Invalid quantity';
            break;
        }
        
        $product = getProductById($productId);
        if (!$product) {
            $response['message'] = 'Product not found';
            break;
        }
        
        if ($product['stock'] < $quantity) {
            $response['message'] = 'Insufficient stock';
            break;
        }
        
        if (addToCart($productId, $quantity)) {
            $response['success'] = true;
            $response['message'] = 'Product added to cart';
            $response['cart_count'] = getCartCount();
            $response['cart_total'] = getCartTotal();
        } else {
            $response['message'] = 'Failed to add product to cart';
        }
        break;
        
    case 'update':
        if (!isset($input['product_id']) || !isset($input['quantity'])) {
            $response['message'] = 'Missing required parameters';
            break;
        }
        
        $productId = intval($input['product_id']);
        $quantity = intval($input['quantity']);
        
        if (updateCartQuantity($productId, $quantity)) {
            $response['success'] = true;
            $response['message'] = 'Cart updated';
            $response['cart_count'] = getCartCount();
            $response['cart_total'] = getCartTotal();
        } else {
            $response['message'] = 'Failed to update cart';
        }
        break;
        
    case 'remove':
        if (!isset($input['product_id'])) {
            $response['message'] = 'Missing product ID';
            break;
        }
        
        $productId = intval($input['product_id']);
        
        if (removeFromCart($productId)) {
            $response['success'] = true;
            $response['message'] = 'Product removed from cart';
            $response['cart_count'] = getCartCount();
            $response['cart_total'] = getCartTotal();
        } else {
            $response['message'] = 'Failed to remove product from cart';
        }
        break;
        
    case 'clear':
        clearCart();
        $response['success'] = true;
        $response['message'] = 'Cart cleared';
        $response['cart_count'] = 0;
        $response['cart_total'] = 0;
        break;
        
    case 'get':
        $response['success'] = true;
        $response['items'] = getCartItems();
        $response['cart_count'] = getCartCount();
        $response['cart_total'] = getCartTotal();
        break;
        
    default:
        $response['message'] = 'Invalid action';
        break;
}

echo json_encode($response);
?>
