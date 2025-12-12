<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['order_id']) || !isset($input['payment_method'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}

$orderId = intval($input['order_id']);
$paymentMethod = sanitize($input['payment_method']);

// Get order details
$order = getOrderById($orderId);
if (!$order) {
    echo json_encode(['success' => false, 'message' => 'Order not found']);
    exit();
}

// Check if already paid
if ($order['payment_status'] === 'completed') {
    echo json_encode(['success' => false, 'message' => 'Order already paid']);
    exit();
}

$response = ['success' => false];

if ($paymentMethod === 'airtel_money') {
    // Airtel Money Payment Processing
    if (!isset($input['transaction_id']) || empty(trim($input['transaction_id']))) {
        echo json_encode(['success' => false, 'message' => 'Transaction ID is required']);
        exit();
    }
    
    $transactionId = sanitize($input['transaction_id']);
    
    // In a real implementation, you would verify the transaction with Airtel Money API
    // For now, we'll accept any transaction ID and mark as completed
    
    // Update payment status
    if (updatePaymentStatus($orderId, 'completed', $transactionId)) {
        updateOrderStatus($orderId, 'processing');
        
        // Send confirmation emails
        sendOrderConfirmation($orderId);
        sendAdminOrderNotification($orderId);
        
        // Clear cart
        clearCart();
        
        $response['success'] = true;
        $response['message'] = 'Payment confirmed successfully';
    } else {
        $response['message'] = 'Failed to update payment status';
    }
    
} elseif ($paymentMethod === 'card') {
    // Stripe Card Payment Processing
    if (!isset($input['stripe_token']) || empty(trim($input['stripe_token']))) {
        echo json_encode(['success' => false, 'message' => 'Payment token is required']);
        exit();
    }
    
    $stripeToken = sanitize($input['stripe_token']);
    
    try {
        // Initialize Stripe
        require_once __DIR__ . '/../vendor/autoload.php';
        \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
        
        // Create charge
        $charge = \Stripe\Charge::create([
            'amount' => intval($order['total_amount'] * 100), // Amount in cents
            'currency' => strtolower(CURRENCY_CODE),
            'source' => $stripeToken,
            'description' => 'Order #' . $orderId . ' - ' . SITE_NAME,
            'metadata' => [
                'order_id' => $orderId,
                'customer_email' => $order['customer_email']
            ]
        ]);
        
        if ($charge->status === 'succeeded') {
            // Update payment status
            if (updatePaymentStatus($orderId, 'completed', $charge->id)) {
                updateOrderStatus($orderId, 'processing');
                
                // Send confirmation emails
                sendOrderConfirmation($orderId);
                sendAdminOrderNotification($orderId);
                
                // Clear cart
                clearCart();
                
                $response['success'] = true;
                $response['message'] = 'Payment successful';
                $response['transaction_id'] = $charge->id;
            } else {
                $response['message'] = 'Payment successful but failed to update order';
            }
        } else {
            $response['message'] = 'Payment failed: ' . $charge->status;
        }
        
    } catch (\Stripe\Exception\CardException $e) {
        $response['message'] = 'Card error: ' . $e->getError()->message;
    } catch (\Stripe\Exception\RateLimitException $e) {
        $response['message'] = 'Too many requests. Please try again later.';
    } catch (\Stripe\Exception\InvalidRequestException $e) {
        $response['message'] = 'Invalid request. Please try again.';
    } catch (\Stripe\Exception\AuthenticationException $e) {
        $response['message'] = 'Authentication error. Please contact support.';
    } catch (\Stripe\Exception\ApiConnectionException $e) {
        $response['message'] = 'Network error. Please try again.';
    } catch (\Stripe\Exception\ApiErrorException $e) {
        $response['message'] = 'Payment processing error. Please try again.';
    } catch (Exception $e) {
        $response['message'] = 'Error processing payment: ' . $e->getMessage();
        error_log('Stripe payment error: ' . $e->getMessage());
    }
    
} else {
    $response['message'] = 'Invalid payment method';
}

echo json_encode($response);
?>
