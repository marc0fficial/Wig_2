<?php
include 'includes/header.php';

// Get product ID from URL
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$productId) {
    header('Location: products.php');
    exit();
}

// Get product details
$product = getProductById($productId);

if (!$product) {
    header('Location: products.php');
    exit();
}

$pageTitle = $product['name'];

// Get related products (same category)
$relatedProducts = getProductsByCategory($product['category']);
// Remove current product from related products
$relatedProducts = array_filter($relatedProducts, function($p) use ($productId) {
    return $p['id'] != $productId;
});
$relatedProducts = array_slice($relatedProducts, 0, 3);
?>

<!-- Breadcrumb -->
<nav class="bg-gray-100 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="index.php" class="text-primary hover:text-purple-700">Home</a></li>
            <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
            <li><a href="products.php" class="text-primary hover:text-purple-700">Products</a></li>
            <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
            <li class="text-gray-600"><?php echo htmlspecialchars($product['name']); ?></li>
        </ol>
    </div>
</nav>

<!-- Product Detail Section -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Product Image -->
            <div class="space-y-4">
                <div class="bg-gray-200 rounded-lg overflow-hidden shadow-lg">
                    <?php if ($product['image']): ?>
                    <img src="<?php echo SITE_URL; ?>/assets/images/products/<?php echo htmlspecialchars($product['image']); ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>" 
                         class="w-full h-96 object-cover">
                    <?php else: ?>
                    <div class="w-full h-96 flex items-center justify-center">
                        <i class="fas fa-image text-gray-400 text-9xl"></i>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Product Features -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <i class="fas fa-shield-alt text-primary text-2xl mb-2"></i>
                        <p class="text-sm text-gray-600">Quality Guaranteed</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <i class="fas fa-truck text-secondary text-2xl mb-2"></i>
                        <p class="text-sm text-gray-600">Fast Shipping</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <i class="fas fa-undo text-primary text-2xl mb-2"></i>
                        <p class="text-sm text-gray-600">Easy Returns</p>
                    </div>
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="space-y-6">
                <div>
                    <span class="inline-block bg-primary text-white px-3 py-1 rounded-full text-sm font-semibold mb-3">
                        <?php echo htmlspecialchars($product['category']); ?>
                    </span>
                    <?php if ($product['featured']): ?>
                    <span class="inline-block bg-secondary text-white px-3 py-1 rounded-full text-sm font-semibold mb-3 ml-2">
                        Featured
                    </span>
                    <?php endif; ?>
                    
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </h1>
                    
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="text-gray-600">(5.0 rating)</span>
                    </div>
                    
                    <div class="text-4xl font-bold text-primary mb-6">
                        <?php echo formatPrice($product['price']); ?>
                    </div>
                </div>
                
                <div class="border-t border-b border-gray-200 py-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                    <p class="text-gray-600 leading-relaxed">
                        <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                    </p>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700 font-medium">Availability:</span>
                        <?php if ($product['stock'] > 0): ?>
                        <span class="text-green-600 font-semibold">
                            <i class="fas fa-check-circle"></i> In Stock (<?php echo $product['stock']; ?> available)
                        </span>
                        <?php else: ?>
                        <span class="text-red-600 font-semibold">
                            <i class="fas fa-times-circle"></i> Out of Stock
                        </span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700 font-medium">Category:</span>
                        <a href="products.php?category=<?php echo urlencode($product['category']); ?>" 
                           class="text-primary hover:text-purple-700">
                            <?php echo htmlspecialchars($product['category']); ?> Wigs
                        </a>
                    </div>
                </div>
                
                <!-- Add to Cart Form -->
                <form id="addToCartForm" class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <label class="text-gray-700 font-medium">Quantity:</label>
                        <div class="flex items-center border border-gray-300 rounded-lg">
                            <button type="button" onclick="decreaseQuantity()" 
                                    class="px-4 py-2 text-gray-600 hover:bg-gray-100">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" 
                                   max="<?php echo $product['stock']; ?>" 
                                   class="w-16 text-center border-x border-gray-300 py-2 focus:outline-none">
                            <button type="button" onclick="increaseQuantity()" 
                                    class="px-4 py-2 text-gray-600 hover:bg-gray-100">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex space-x-4">
                        <?php if ($product['stock'] > 0): ?>
                        <button type="submit" 
                                class="flex-1 bg-primary hover:bg-purple-700 text-white px-8 py-4 rounded-lg font-semibold transition duration-300 shadow-lg">
                            <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
                        </button>
                        <a href="checkout.php?buy_now=<?php echo $product['id']; ?>" 
                           class="bg-secondary hover:bg-orange-600 text-white px-8 py-4 rounded-lg font-semibold transition duration-300 shadow-lg">
                            Buy Now
                        </a>
                        <?php else: ?>
                        <button type="button" disabled 
                                class="flex-1 bg-gray-400 text-white px-8 py-4 rounded-lg font-semibold cursor-not-allowed">
                            Out of Stock
                        </button>
                        <?php endif; ?>
                    </div>
                </form>
                
                <!-- Contact Info -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="font-semibold text-gray-900 mb-3">Need Help?</h3>
                    <div class="space-y-2">
                        <p class="text-gray-600">
                            <i class="fas fa-phone text-primary mr-2"></i>
                            Call us: <?php echo ADMIN_PHONE; ?>
                        </p>
                        <p class="text-gray-600">
                            <i class="fas fa-envelope text-primary mr-2"></i>
                            Email: <?php echo ADMIN_EMAIL; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
<?php if (count($relatedProducts) > 0): ?>
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Related Products</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($relatedProducts as $relatedProduct): ?>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                <div class="relative h-64 bg-gray-200">
                    <?php if ($relatedProduct['image']): ?>
                    <img src="<?php echo SITE_URL; ?>/assets/images/products/<?php echo htmlspecialchars($relatedProduct['image']); ?>" 
                         alt="<?php echo htmlspecialchars($relatedProduct['name']); ?>" 
                         class="w-full h-full object-cover">
                    <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="fas fa-image text-gray-400 text-6xl"></i>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        <?php echo htmlspecialchars($relatedProduct['name']); ?>
                    </h3>
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-primary">
                            <?php echo formatPrice($relatedProduct['price']); ?>
                        </span>
                        <a href="product-detail.php?id=<?php echo $relatedProduct['id']; ?>" 
                           class="bg-primary hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition duration-300">
                            View
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
const maxStock = <?php echo $product['stock']; ?>;

function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    let currentValue = parseInt(quantityInput.value);
    if (currentValue < maxStock) {
        quantityInput.value = currentValue + 1;
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    let currentValue = parseInt(quantityInput.value);
    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
    }
}

document.getElementById('addToCartForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const quantity = document.getElementById('quantity').value;
    
    fetch('<?php echo SITE_URL; ?>/api/cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'add',
            product_id: <?php echo $productId; ?>,
            quantity: parseInt(quantity)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Product added to cart successfully!');
            window.location.href = 'cart.php';
        } else {
            alert('Error adding product to cart: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error adding product to cart');
    });
});
</script>

<?php include 'includes/footer.php'; ?>
