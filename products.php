<?php
$pageTitle = "Shop All Wigs";
include 'includes/header.php';

// Get search query and category filter
$searchQuery = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$categoryFilter = isset($_GET['category']) ? sanitize($_GET['category']) : '';

// Get products based on filters
if ($searchQuery) {
    $products = searchProducts($searchQuery);
    $pageTitle = "Search Results for: " . htmlspecialchars($searchQuery);
} elseif ($categoryFilter) {
    $products = getProductsByCategory($categoryFilter);
    $pageTitle = htmlspecialchars($categoryFilter) . " Wigs";
} else {
    $products = getAllProducts();
}

// Get all categories
$categories = getCategories();
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-primary to-purple-600 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold mb-2"><?php echo $pageTitle; ?></h1>
        <p class="text-gray-100">Browse our complete collection of premium wigs</p>
    </div>
</section>

<!-- Products Section -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <aside class="lg:w-64 flex-shrink-0">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-20">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Categories</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="products.php" 
                               class="block px-4 py-2 rounded-lg <?php echo !$categoryFilter ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100'; ?> transition duration-300">
                                All Wigs
                            </a>
                        </li>
                        <?php foreach ($categories as $cat): ?>
                        <li>
                            <a href="products.php?category=<?php echo urlencode($cat['category']); ?>" 
                               class="block px-4 py-2 rounded-lg <?php echo $categoryFilter === $cat['category'] ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100'; ?> transition duration-300">
                                <?php echo htmlspecialchars($cat['category']); ?> Wigs
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    
                    <!-- Price Range Info -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Price Range</h3>
                        <p class="text-gray-600 text-sm">$100 - $200</p>
                    </div>
                    
                    <!-- Contact Info -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Need Help?</h3>
                        <p class="text-gray-600 text-sm mb-2">Contact us:</p>
                        <p class="text-primary font-semibold"><?php echo ADMIN_PHONE; ?></p>
                    </div>
                </div>
            </aside>
            
            <!-- Products Grid -->
            <main class="flex-1">
                <?php if (count($products) > 0): ?>
                <div class="mb-6 flex justify-between items-center">
                    <p class="text-gray-600">
                        Showing <span class="font-semibold"><?php echo count($products); ?></span> products
                    </p>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($products as $product): ?>
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300 transform hover:-translate-y-2">
                        <div class="relative h-64 bg-gray-200">
                            <?php if ($product['image']): ?>
                            <img src="<?php echo SITE_URL; ?>/assets/images/products/<?php echo htmlspecialchars($product['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                 class="w-full h-full object-cover">
                            <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-6xl"></i>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($product['featured']): ?>
                            <span class="absolute top-4 right-4 bg-secondary text-white px-3 py-1 rounded-full text-sm font-semibold">
                                Featured
                            </span>
                            <?php endif; ?>
                            
                            <span class="absolute top-4 left-4 bg-primary text-white px-3 py-1 rounded-full text-sm font-semibold">
                                <?php echo htmlspecialchars($product['category']); ?>
                            </span>
                        </div>
                        
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                <?php echo htmlspecialchars($product['name']); ?>
                            </h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                <?php echo htmlspecialchars($product['description']); ?>
                            </p>
                            
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-2xl font-bold text-primary">
                                    <?php echo formatPrice($product['price']); ?>
                                </span>
                                <span class="text-sm text-gray-500">
                                    <i class="fas fa-box"></i> <?php echo $product['stock']; ?> in stock
                                </span>
                            </div>
                            
                            <div class="flex space-x-2">
                                <a href="product-detail.php?id=<?php echo $product['id']; ?>" 
                                   class="flex-1 bg-primary hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition duration-300 text-center">
                                    View Details
                                </a>
                                <button onclick="addToCartQuick(<?php echo $product['id']; ?>)" 
                                        class="bg-secondary hover:bg-orange-600 text-white px-4 py-2 rounded-lg transition duration-300">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <?php else: ?>
                <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                    <i class="fas fa-search text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-2xl font-semibold text-gray-900 mb-2">No Products Found</h3>
                    <p class="text-gray-600 mb-6">
                        <?php if ($searchQuery): ?>
                            No products match your search "<?php echo htmlspecialchars($searchQuery); ?>"
                        <?php elseif ($categoryFilter): ?>
                            No products found in this category
                        <?php else: ?>
                            No products available at the moment
                        <?php endif; ?>
                    </p>
                    <a href="products.php" class="inline-block bg-primary hover:bg-purple-700 text-white px-6 py-3 rounded-lg transition duration-300">
                        View All Products
                    </a>
                </div>
                <?php endif; ?>
            </main>
        </div>
    </div>
</section>

<script>
function addToCartQuick(productId) {
    fetch('<?php echo SITE_URL; ?>/api/cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'add',
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Product added to cart!');
            location.reload();
        } else {
            alert('Error adding product to cart');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error adding product to cart');
    });
}
</script>

<?php include 'includes/footer.php'; ?>
