<?php
$pageTitle = "Home";
include 'includes/header.php';

// Get featured products
$featuredProducts = getAllProducts(6, true);
?>

<!-- Hero Section -->
<section class="relative bg-gradient-to-r from-primary to-purple-600 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <h1 class="text-5xl md:text-6xl font-bold leading-tight">
                    Discover Your Perfect <span class="text-secondary">Wig</span>
                </h1>
                <p class="text-xl text-gray-100">
                    Premium quality wigs for every style and occasion. Transform your look with confidence.
                </p>
                <div class="flex space-x-4">
                    <a href="products.php" class="bg-secondary hover:bg-orange-600 text-white px-8 py-3 rounded-lg font-semibold transition duration-300 shadow-lg">
                        Shop Now
                    </a>
                    <a href="#featured" class="bg-white text-primary hover:bg-gray-100 px-8 py-3 rounded-lg font-semibold transition duration-300 shadow-lg">
                        View Collection
                    </a>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="relative">
                    <div class="absolute inset-0 bg-secondary rounded-full blur-3xl opacity-20"></div>
                    <i class="fas fa-crown text-white text-9xl relative z-10 animate-pulse"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="bg-primary bg-opacity-10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shipping-fast text-primary text-2xl"></i>
                </div>
                <h3 class="font-semibold text-lg mb-2">Fast Delivery</h3>
                <p class="text-gray-600 text-sm">Quick and reliable shipping</p>
            </div>
            <div class="text-center">
                <div class="bg-secondary bg-opacity-10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-secondary text-2xl"></i>
                </div>
                <h3 class="font-semibold text-lg mb-2">Quality Guaranteed</h3>
                <p class="text-gray-600 text-sm">100% authentic products</p>
            </div>
            <div class="text-center">
                <div class="bg-primary bg-opacity-10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-credit-card text-primary text-2xl"></i>
                </div>
                <h3 class="font-semibold text-lg mb-2">Secure Payment</h3>
                <p class="text-gray-600 text-sm">Multiple payment options</p>
            </div>
            <div class="text-center">
                <div class="bg-secondary bg-opacity-10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-headset text-secondary text-2xl"></i>
                </div>
                <h3 class="font-semibold text-lg mb-2">24/7 Support</h3>
                <p class="text-gray-600 text-sm">Always here to help</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section id="featured" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">
                Featured <span class="text-primary">Collection</span>
            </h2>
            <p class="text-gray-600 text-lg">Discover our most popular and trending wigs</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($featuredProducts as $product): ?>
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
                    <span class="absolute top-4 right-4 bg-secondary text-white px-3 py-1 rounded-full text-sm font-semibold">
                        Featured
                    </span>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                        <?php echo htmlspecialchars($product['description']); ?>
                    </p>
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-primary">
                            <?php echo formatPrice($product['price']); ?>
                        </span>
                        <a href="product-detail.php?id=<?php echo $product['id']; ?>" 
                           class="bg-primary hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition duration-300">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-12">
            <a href="products.php" class="inline-block bg-secondary hover:bg-orange-600 text-white px-8 py-3 rounded-lg font-semibold transition duration-300 shadow-lg">
                View All Products
            </a>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-4xl font-bold text-gray-900 mb-6">
                    Why Choose <span class="text-primary"><?php echo SITE_NAME; ?></span>?
                </h2>
                <p class="text-gray-600 mb-4">
                    We are dedicated to providing the highest quality wigs that look natural and feel comfortable. Our collection features a wide variety of styles, colors, and textures to suit every preference.
                </p>
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-secondary text-xl mr-3 mt-1"></i>
                        <span class="text-gray-700">100% premium quality human hair and synthetic wigs</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-secondary text-xl mr-3 mt-1"></i>
                        <span class="text-gray-700">Natural-looking styles for every occasion</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-secondary text-xl mr-3 mt-1"></i>
                        <span class="text-gray-700">Affordable prices without compromising quality</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-secondary text-xl mr-3 mt-1"></i>
                        <span class="text-gray-700">Expert customer service and support</span>
                    </li>
                </ul>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-primary bg-opacity-10 p-6 rounded-lg text-center">
                    <div class="text-4xl font-bold text-primary mb-2">500+</div>
                    <div class="text-gray-600">Happy Customers</div>
                </div>
                <div class="bg-secondary bg-opacity-10 p-6 rounded-lg text-center">
                    <div class="text-4xl font-bold text-secondary mb-2">100+</div>
                    <div class="text-gray-600">Wig Styles</div>
                </div>
                <div class="bg-secondary bg-opacity-10 p-6 rounded-lg text-center">
                    <div class="text-4xl font-bold text-secondary mb-2">5★</div>
                    <div class="text-gray-600">Average Rating</div>
                </div>
                <div class="bg-primary bg-opacity-10 p-6 rounded-lg text-center">
                    <div class="text-4xl font-bold text-primary mb-2">24/7</div>
                    <div class="text-gray-600">Support</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">
                Get In <span class="text-primary">Touch</span>
            </h2>
            <p class="text-gray-600 text-lg">Have questions? We'd love to hear from you!</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-lg shadow-lg text-center">
                <div class="bg-primary bg-opacity-10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-phone text-primary text-2xl"></i>
                </div>
                <h3 class="font-semibold text-lg mb-2">Call Us</h3>
                <p class="text-gray-600"><?php echo ADMIN_PHONE; ?></p>
            </div>
            
            <div class="bg-white p-8 rounded-lg shadow-lg text-center">
                <div class="bg-secondary bg-opacity-10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-envelope text-secondary text-2xl"></i>
                </div>
                <h3 class="font-semibold text-lg mb-2">Email Us</h3>
                <p class="text-gray-600"><?php echo ADMIN_EMAIL; ?></p>
            </div>
            
            <div class="bg-white p-8 rounded-lg shadow-lg text-center">
                <div class="bg-primary bg-opacity-10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fab fa-whatsapp text-primary text-2xl"></i>
                </div>
                <h3 class="font-semibold text-lg mb-2">WhatsApp</h3>
                <p class="text-gray-600"><?php echo ADMIN_PHONE; ?></p>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
