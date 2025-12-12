<?php
$pageTitle = "Checkout";
include 'includes/header.php';

// Check if cart is empty
$cartItems = getCartItems();
if (count($cartItems) === 0 && !isset($_GET['buy_now'])) {
    header('Location: cart.php');
    exit();
}

// Handle buy now
if (isset($_GET['buy_now'])) {
    $productId = intval($_GET['buy_now']);
    $product = getProductById($productId);
    if ($product) {
        clearCart();
        addToCart($productId, 1);
        $cartItems = getCartItems();
    }
}

$cartTotal = getCartTotal();
$shippingCost = $cartTotal >= FREE_SHIPPING_THRESHOLD ? 0 : SHIPPING_COST;
$finalTotal = $cartTotal + $shippingCost;
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-primary to-purple-600 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold mb-2">Checkout</h1>
        <p class="text-gray-100">Complete your order</p>
    </div>
</section>

<!-- Checkout Section -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Checkout Form -->
            <div class="lg:col-span-2">
                <form id="checkoutForm" class="space-y-6">
                    <!-- Customer Information -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-user text-primary mr-3"></i>
                            Customer Information
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Full Name *</label>
                                <input type="text" name="name" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                       placeholder="John Doe">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Phone Number *</label>
                                <input type="tel" name="phone" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                       placeholder="0798 974 781">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-gray-700 font-medium mb-2">Email Address *</label>
                                <input type="email" name="email" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                       placeholder="john@example.com">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Shipping Address -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-map-marker-alt text-primary mr-3"></i>
                            Shipping Address
                        </h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Street Address *</label>
                                <input type="text" name="address" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                       placeholder="123 Main Street">
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">City *</label>
                                    <input type="text" name="city" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                           placeholder="Kigali">
                                </div>
                                
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">District *</label>
                                    <input type="text" name="district" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                           placeholder="Gasabo">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Additional Notes (Optional)</label>
                                <textarea name="notes" rows="3" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                          placeholder="Any special delivery instructions..."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Method -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-credit-card text-primary mr-3"></i>
                            Payment Method
                        </h2>
                        
                        <div class="space-y-4">
                            <!-- Airtel Money -->
                            <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-primary transition duration-300">
                                <input type="radio" name="payment_method" value="airtel_money" required class="mr-4">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-mobile-alt text-primary text-2xl mr-3"></i>
                                            <div>
                                                <p class="font-semibold text-gray-900">Airtel Money</p>
                                                <p class="text-sm text-gray-600">Pay with Airtel Money mobile wallet</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            
                            <!-- Visa/Card Payment -->
                            <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-primary transition duration-300">
                                <input type="radio" name="payment_method" value="card" required class="mr-4">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fab fa-cc-visa text-blue-600 text-2xl mr-3"></i>
                                            <div>
                                                <p class="font-semibold text-gray-900">Credit/Debit Card</p>
                                                <p class="text-sm text-gray-600">Pay securely with Visa, Mastercard</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Terms and Conditions -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <label class="flex items-start cursor-pointer">
                            <input type="checkbox" name="terms" required class="mt-1 mr-3">
                            <span class="text-gray-700">
                                I agree to the <a href="#" class="text-primary hover:text-purple-700">Terms and Conditions</a> 
                                and <a href="#" class="text-primary hover:text-purple-700">Privacy Policy</a>
                            </span>
                        </label>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-primary hover:bg-purple-700 text-white px-8 py-4 rounded-lg font-semibold transition duration-300 shadow-lg text-lg">
                        <i class="fas fa-lock mr-2"></i> Place Order
                    </button>
                </form>
            </div>
            
            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-20">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Order Summary</h2>
                    
                    <!-- Cart Items -->
                    <div class="space-y-4 mb-6 max-h-64 overflow-y-auto">
                        <?php foreach ($cartItems as $item): ?>
                        <div class="flex items-center space-x-3 pb-3 border-b border-gray-200">
                            <div class="w-16 h-16 bg-gray-200 rounded overflow-hidden flex-shrink-0">
                                <?php if ($item['image']): ?>
                                <img src="<?php echo SITE_URL; ?>/assets/images/products/<?php echo htmlspecialchars($item['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                     class="w-full h-full object-cover">
                                <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 text-sm"><?php echo htmlspecialchars($item['name']); ?></p>
                                <p class="text-gray-600 text-sm">Qty: <?php echo $item['quantity']; ?></p>
                            </div>
                            <p class="font-semibold text-primary"><?php echo formatPrice($item['price'] * $item['quantity']); ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Totals -->
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span class="font-semibold"><?php echo formatPrice($cartTotal); ?></span>
                        </div>
                        
                        <div class="flex justify-between text-gray-600">
                            <span>Shipping</span>
                            <span class="font-semibold">
                                <?php if ($shippingCost > 0): ?>
                                    <?php echo formatPrice($shippingCost); ?>
                                <?php else: ?>
                                    <span class="text-green-600">FREE</span>
                                <?php endif; ?>
                            </span>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between text-xl font-bold text-gray-900">
                                <span>Total</span>
                                <span class="text-primary"><?php echo formatPrice($finalTotal); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Security Badge -->
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <i class="fas fa-shield-alt text-green-600 text-3xl mb-2"></i>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-lock mr-1"></i>
                            Secure SSL Encrypted Payment
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        name: formData.get('name'),
        phone: formData.get('phone'),
        email: formData.get('email'),
        address: formData.get('address') + ', ' + formData.get('city') + ', ' + formData.get('district'),
        notes: formData.get('notes'),
        payment_method: formData.get('payment_method')
    };
    
    // Show loading
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
    submitBtn.disabled = true;
    
    // Create order
    fetch('<?php echo SITE_URL; ?>/api/orders.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            // Redirect to payment page
            window.location.href = 'payment.php?order_id=' + result.order_id;
        } else {
            alert('Error creating order: ' + (result.message || 'Unknown error'));
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error creating order. Please try again.');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});
</script>

<?php include 'includes/footer.php'; ?>
