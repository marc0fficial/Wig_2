<?php
$pageTitle = "Shopping Cart";
include 'includes/header.php';

$cartItems = getCartItems();
$cartTotal = getCartTotal();
$shippingCost = $cartTotal >= FREE_SHIPPING_THRESHOLD ? 0 : SHIPPING_COST;
$finalTotal = $cartTotal + $shippingCost;
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-primary to-purple-600 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold mb-2">Shopping Cart</h1>
        <p class="text-gray-100">Review your items before checkout</p>
    </div>
</section>

<!-- Cart Section -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if (count($cartItems) > 0): ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2 space-y-4">
                <?php foreach ($cartItems as $item): ?>
                <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col sm:flex-row gap-6">
                    <!-- Product Image -->
                    <div class="w-full sm:w-32 h-32 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                        <?php if ($item['image']): ?>
                        <img src="<?php echo SITE_URL; ?>/assets/images/products/<?php echo htmlspecialchars($item['image']); ?>" 
                             alt="<?php echo htmlspecialchars($item['name']); ?>" 
                             class="w-full h-full object-cover">
                        <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-image text-gray-400 text-4xl"></i>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Product Info -->
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-semibold text-gray-900">
                                <?php echo htmlspecialchars($item['name']); ?>
                            </h3>
                            <button onclick="removeFromCart(<?php echo $item['id']; ?>)" 
                                    class="text-red-500 hover:text-red-700 transition duration-300">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        
                        <p class="text-gray-600 mb-4">
                            Price: <span class="font-semibold text-primary"><?php echo formatPrice($item['price']); ?></span>
                        </p>
                        
                        <div class="flex items-center justify-between">
                            <!-- Quantity Controls -->
                            <div class="flex items-center border border-gray-300 rounded-lg">
                                <button onclick="updateQuantity(<?php echo $item['id']; ?>, <?php echo $item['quantity'] - 1; ?>)" 
                                        class="px-3 py-1 text-gray-600 hover:bg-gray-100">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span class="px-4 py-1 border-x border-gray-300"><?php echo $item['quantity']; ?></span>
                                <button onclick="updateQuantity(<?php echo $item['id']; ?>, <?php echo $item['quantity'] + 1; ?>)" 
                                        class="px-3 py-1 text-gray-600 hover:bg-gray-100">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            
                            <!-- Subtotal -->
                            <div class="text-right">
                                <p class="text-sm text-gray-600">Subtotal</p>
                                <p class="text-xl font-bold text-primary">
                                    <?php echo formatPrice($item['price'] * $item['quantity']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <!-- Continue Shopping Button -->
                <div class="flex justify-between items-center pt-4">
                    <a href="products.php" class="text-primary hover:text-purple-700 font-semibold">
                        <i class="fas fa-arrow-left mr-2"></i> Continue Shopping
                    </a>
                    <button onclick="clearCart()" class="text-red-500 hover:text-red-700 font-semibold">
                        <i class="fas fa-trash mr-2"></i> Clear Cart
                    </button>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-20">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Order Summary</h2>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal (<?php echo getCartCount(); ?> items)</span>
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
                        
                        <?php if ($cartTotal < FREE_SHIPPING_THRESHOLD): ?>
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-3">
                            <p class="text-sm text-orange-800">
                                <i class="fas fa-info-circle mr-1"></i>
                                Add <?php echo formatPrice(FREE_SHIPPING_THRESHOLD - $cartTotal); ?> more for FREE shipping!
                            </p>
                        </div>
                        <?php else: ?>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                            <p class="text-sm text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                You qualify for FREE shipping!
                            </p>
                        </div>
                        <?php endif; ?>
                        
                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between text-xl font-bold text-gray-900">
                                <span>Total</span>
                                <span class="text-primary"><?php echo formatPrice($finalTotal); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <a href="checkout.php" 
                       class="block w-full bg-primary hover:bg-purple-700 text-white text-center px-6 py-4 rounded-lg font-semibold transition duration-300 shadow-lg mb-3">
                        Proceed to Checkout
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    
                    <div class="text-center text-sm text-gray-600">
                        <i class="fas fa-lock mr-1"></i>
                        Secure Checkout
                    </div>
                    
                    <!-- Payment Methods -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-600 mb-3 text-center">We Accept</p>
                        <div class="flex justify-center space-x-4">
                            <div class="bg-gray-100 px-3 py-2 rounded">
                                <i class="fas fa-mobile-alt text-primary text-xl"></i>
                            </div>
                            <div class="bg-gray-100 px-3 py-2 rounded">
                                <i class="fab fa-cc-visa text-blue-600 text-xl"></i>
                            </div>
                            <div class="bg-gray-100 px-3 py-2 rounded">
                                <i class="fab fa-cc-mastercard text-red-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php else: ?>
        <!-- Empty Cart -->
        <div class="bg-white rounded-lg shadow-lg p-12 text-center max-w-2xl mx-auto">
            <i class="fas fa-shopping-cart text-gray-400 text-6xl mb-4"></i>
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Your Cart is Empty</h2>
            <p class="text-gray-600 mb-8">
                Looks like you haven't added any items to your cart yet. Start shopping to fill it up!
            </p>
            <a href="products.php" 
               class="inline-block bg-primary hover:bg-purple-700 text-white px-8 py-3 rounded-lg font-semibold transition duration-300 shadow-lg">
                <i class="fas fa-shopping-bag mr-2"></i> Start Shopping
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>

<script>
function updateQuantity(productId, quantity) {
    if (quantity < 1) {
        if (confirm('Remove this item from cart?')) {
            removeFromCart(productId);
        }
        return;
    }
    
    fetch('<?php echo SITE_URL; ?>/api/cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'update',
            product_id: productId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error updating cart: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating cart');
    });
}

function removeFromCart(productId) {
    if (!confirm('Are you sure you want to remove this item?')) {
        return;
    }
    
    fetch('<?php echo SITE_URL; ?>/api/cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'remove',
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error removing item: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error removing item');
    });
}

function clearCart() {
    if (!confirm('Are you sure you want to clear your entire cart?')) {
        return;
    }
    
    fetch('<?php echo SITE_URL; ?>/api/cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'clear'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error clearing cart: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error clearing cart');
    });
}
</script>

<?php include 'includes/footer.php'; ?>
