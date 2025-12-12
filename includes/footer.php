<footer class="bg-gray-900 text-white mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- About Section -->
            <div>
                <div class="flex items-center space-x-2 mb-4">
                    <i class="fas fa-crown text-primary text-2xl"></i>
                    <span class="text-xl font-bold"><?php echo SITE_NAME; ?></span>
                </div>
                <p class="text-gray-400 text-sm">
                    Your premier destination for high-quality wigs. We offer a wide selection of beautiful, natural-looking wigs for every style and occasion.
                </p>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-semibold mb-4 text-secondary">Quick Links</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="<?php echo SITE_URL; ?>/index.php" class="text-gray-400 hover:text-primary transition duration-300">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL; ?>/products.php" class="text-gray-400 hover:text-primary transition duration-300">
                            Shop All Wigs
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL; ?>/cart.php" class="text-gray-400 hover:text-primary transition duration-300">
                            Shopping Cart
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL; ?>/admin/login.php" class="text-gray-400 hover:text-primary transition duration-300">
                            Admin Login
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Categories -->
            <div>
                <h3 class="text-lg font-semibold mb-4 text-secondary">Categories</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="<?php echo SITE_URL; ?>/products.php?category=Straight" class="text-gray-400 hover:text-primary transition duration-300">
                            Straight Wigs
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL; ?>/products.php?category=Curly" class="text-gray-400 hover:text-primary transition duration-300">
                            Curly Wigs
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL; ?>/products.php?category=Wavy" class="text-gray-400 hover:text-primary transition duration-300">
                            Wavy Wigs
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL; ?>/products.php?category=Bob" class="text-gray-400 hover:text-primary transition duration-300">
                            Bob Wigs
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div>
                <h3 class="text-lg font-semibold mb-4 text-secondary">Contact Us</h3>
                <ul class="space-y-3">
                    <li class="flex items-center space-x-2">
                        <i class="fas fa-phone text-primary"></i>
                        <span class="text-gray-400"><?php echo ADMIN_PHONE; ?></span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i class="fas fa-envelope text-primary"></i>
                        <span class="text-gray-400"><?php echo ADMIN_EMAIL; ?></span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i class="fas fa-map-marker-alt text-primary"></i>
                        <span class="text-gray-400">Kigali, Rwanda</span>
                    </li>
                </ul>
                
                <!-- Social Media -->
                <div class="flex space-x-4 mt-4">
                    <a href="#" class="text-gray-400 hover:text-primary transition duration-300">
                        <i class="fab fa-facebook text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-primary transition duration-300">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-primary transition duration-300">
                        <i class="fab fa-twitter text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-primary transition duration-300">
                        <i class="fab fa-whatsapp text-xl"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Bottom Bar -->
        <div class="border-t border-gray-800 mt-8 pt-8 text-center">
            <p class="text-gray-400 text-sm">
                &copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.
            </p>
            <p class="text-gray-500 text-xs mt-2">
                Powered by Excellence | Designed with <i class="fas fa-heart text-red-500"></i>
            </p>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button 
    onclick="scrollToTop()" 
    id="backToTop" 
    class="hidden fixed bottom-8 right-8 bg-primary text-white p-3 rounded-full shadow-lg hover:bg-purple-700 transition duration-300 z-50"
>
    <i class="fas fa-arrow-up"></i>
</button>

<script>
// Back to top functionality
window.onscroll = function() {
    const backToTop = document.getElementById('backToTop');
    if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
        backToTop.classList.remove('hidden');
    } else {
        backToTop.classList.add('hidden');
    }
};

function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}
</script>

<!-- Main JavaScript -->
<script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
</body>
</html>
