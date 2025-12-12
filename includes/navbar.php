<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="<?php echo SITE_URL; ?>/index.php" class="flex items-center space-x-2">
                    <i class="fas fa-crown text-primary text-2xl"></i>
                    <span class="text-2xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                        <?php echo SITE_NAME; ?>
                    </span>
                </a>
            </div>
            
            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="<?php echo SITE_URL; ?>/index.php" class="text-gray-700 hover:text-primary transition duration-300 font-medium">
                    Home
                </a>
                <a href="<?php echo SITE_URL; ?>/products.php" class="text-gray-700 hover:text-primary transition duration-300 font-medium">
                    Shop
                </a>
                <a href="<?php echo SITE_URL; ?>/products.php#categories" class="text-gray-700 hover:text-primary transition duration-300 font-medium">
                    Categories
                </a>
                <a href="<?php echo SITE_URL; ?>/index.php#about" class="text-gray-700 hover:text-primary transition duration-300 font-medium">
                    About
                </a>
                <a href="<?php echo SITE_URL; ?>/index.php#contact" class="text-gray-700 hover:text-primary transition duration-300 font-medium">
                    Contact
                </a>
            </div>
            
            <!-- Cart and Mobile Menu -->
            <div class="flex items-center space-x-4">
                <!-- Search Icon -->
                <button onclick="toggleSearch()" class="text-gray-700 hover:text-primary transition duration-300">
                    <i class="fas fa-search text-xl"></i>
                </button>
                
                <!-- Cart -->
                <a href="<?php echo SITE_URL; ?>/cart.php" class="relative text-gray-700 hover:text-primary transition duration-300">
                    <i class="fas fa-shopping-cart text-xl"></i>
                    <?php 
                    $cartCount = getCartCount();
                    if ($cartCount > 0): 
                    ?>
                    <span class="absolute -top-2 -right-2 bg-secondary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">
                        <?php echo $cartCount; ?>
                    </span>
                    <?php endif; ?>
                </a>
                
                <!-- Mobile Menu Button -->
                <button onclick="toggleMobileMenu()" class="md:hidden text-gray-700 hover:text-primary transition duration-300">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Search Bar (Hidden by default) -->
    <div id="searchBar" class="hidden bg-gray-100 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <form action="<?php echo SITE_URL; ?>/products.php" method="GET" class="flex">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Search for wigs..." 
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-primary"
                >
                <button 
                    type="submit" 
                    class="bg-primary text-white px-6 py-2 rounded-r-lg hover:bg-purple-700 transition duration-300"
                >
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>
    
    <!-- Mobile Menu (Hidden by default) -->
    <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-gray-200">
        <div class="px-4 py-4 space-y-3">
            <a href="<?php echo SITE_URL; ?>/index.php" class="block text-gray-700 hover:text-primary transition duration-300 font-medium py-2">
                Home
            </a>
            <a href="<?php echo SITE_URL; ?>/products.php" class="block text-gray-700 hover:text-primary transition duration-300 font-medium py-2">
                Shop
            </a>
            <a href="<?php echo SITE_URL; ?>/products.php#categories" class="block text-gray-700 hover:text-primary transition duration-300 font-medium py-2">
                Categories
            </a>
            <a href="<?php echo SITE_URL; ?>/index.php#about" class="block text-gray-700 hover:text-primary transition duration-300 font-medium py-2">
                About
            </a>
            <a href="<?php echo SITE_URL; ?>/index.php#contact" class="block text-gray-700 hover:text-primary transition duration-300 font-medium py-2">
                Contact
            </a>
        </div>
    </div>
</nav>

<script>
function toggleSearch() {
    const searchBar = document.getElementById('searchBar');
    searchBar.classList.toggle('hidden');
}

function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    mobileMenu.classList.toggle('hidden');
}
</script>
