<?php
$pageTitle = "Order Confirmation";
include 'includes/header.php';

// Get order ID
$orderId = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if (!$orderId) {
    header('Location: index.php');
    exit();
}

// Get order details
$order = getOrderById($orderId);
if (!$order) {
    header('Location: index.php');
    exit();
}

$orderItems = getOrderItems($orderId);
?>

<!-- Success Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Message -->
        <div class="bg-white rounded-lg shadow-lg p-8 text-center mb-8">
            <div class="mb-6">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                    <i class="fas fa-check text-green-600 text-4xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Order Confirmed!</h1>
                <p class="text-xl text-gray-600">Thank you for your purchase</p>
            </div>
            
            <div class="bg-primary bg-opacity-10 rounded-lg p-6 mb-6">
                <p class="text-gray-700 mb-2">Your order number is:</p>
                <p class="text-3xl font-bold text-primary">#<?php echo $orderId; ?></p>
            </div>
            
            <p class="text-gray-600 mb-6">
                We've sent a confirmation email to <strong><?php echo htmlspecialchars($order['customer_email']); ?></strong>
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="index.php" 
                   class="bg-primary hover:bg-purple-700 text-white px-8 py-3 rounded-lg font-semibold transition duration-300 shadow-lg">
                    <i class="fas fa-home mr-2"></i> Back to Home
                </a>
                <a href="products.php" 
                   class="bg-secondary hover:bg-orange-600 text-white px-8 py-3 rounded-lg font-semibold transition duration-300 shadow-lg">
                    <i class="fas fa-shopping-bag mr-2"></i> Continue Shopping
                </a>
            </div>
        </div>
        
        <!-- Order Details -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Order Details</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="font-semibold text-gray-900 mb-3">Customer Information</h3>
                    <div class="space-y-2 text-gray-600">
                        <p><i class="fas fa-user text-primary mr-2"></i> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                        <p><i class="fas fa-envelope text-primary mr-2"></i> <?php echo htmlspecialchars($order['customer_email']); ?></p>
                        <p><i class="fas fa-phone text-primary mr-2"></i> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
                    </div>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-900 mb-3">Shipping Address</h3>
                    <p class="text-gray-600">
                        <i class="fas fa-map-marker-alt text-primary mr-2"></i>
                        <?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?>
                    </p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold text-gray-900 mb-3">Payment Method</h3>
                    <p class="text-gray-600">
                        <?php if ($order['payment_method'] === 'airtel_money'): ?>
                            <i class="fas fa-mobile-alt text-primary mr-2"></i> Airtel Money
                        <?php else: ?>
                            <i class="fab fa-cc-visa text-blue-600 mr-2"></i> Credit/Debit Card
                        <?php endif; ?>
                    </p>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-900 mb-3">Payment Status</h3>
                    <p>
                        <?php if ($order['payment_status'] === 'completed'): ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Paid
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i> Pending
                            </span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Order Items</h2>
            
            <div class="space-y-4 mb-6">
                <?php foreach ($orderItems as $item): ?>
                <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($item['product_name']); ?></p>
                        <p class="text-gray-600 text-sm">Quantity: <?php echo $item['quantity']; ?> × <?php echo formatPrice($item['price']); ?></p>
                    </div>
                    <p class="font-semibold text-primary"><?php echo formatPrice($item['subtotal']); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="border-t border-gray-200 pt-4">
                <div class="flex justify-between text-xl font-bold text-gray-900">
                    <span>Total Amount</span>
                    <span class="text-primary"><?php echo formatPrice($order['total_amount']); ?></span>
                </div>
            </div>
        </div>
        
        <!-- What's Next -->
        <div class="bg-gradient-to-r from-primary to-purple-600 rounded-lg shadow-lg p-8 text-white mt-8">
            <h2 class="text-2xl font-bold mb-4">What Happens Next?</h2>
            <div class="space-y-3">
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-secondary text-xl mr-3 mt-1"></i>
                    <p>We'll process your order and prepare it for shipping</p>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-secondary text-xl mr-3 mt-1"></i>
                    <p>You'll receive updates via email and SMS</p>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-secondary text-xl mr-3 mt-1"></i>
                    <p>Your order will be delivered within 2-5 business days</p>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-secondary text-xl mr-3 mt-1"></i>
                    <p>Contact us at <?php echo ADMIN_PHONE; ?> for any questions</p>
                </div>
            </div>
        </div>
        
        <!-- Contact Support -->
        <div class="bg-white rounded-lg shadow-lg p-8 mt-8 text-center">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Need Help?</h3>
            <p class="text-gray-600 mb-4">Our customer support team is here to assist you</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="tel:<?php echo ADMIN_PHONE; ?>" 
                   class="inline-flex items-center justify-center bg-primary hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-300">
                    <i class="fas fa-phone mr-2"></i> Call Us
                </a>
                <a href="mailto:<?php echo ADMIN_EMAIL; ?>" 
                   class="inline-flex items-center justify-center bg-secondary hover:bg-orange-600 text-white px-6 py-3 rounded-lg font-semibold transition duration-300">
                    <i class="fas fa-envelope mr-2"></i> Email Us
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
