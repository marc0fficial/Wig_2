<!-- Sidebar -->
<aside id="sidebar" class="bg-gradient-to-b from-primary to-purple-700 text-white w-64 flex-shrink-0 hidden lg:block">
    <div class="p-6">
        <div class="flex items-center space-x-3 mb-8">
            <i class="fas fa-crown text-3xl text-secondary"></i>
            <div>
                <h2 class="text-xl font-bold"><?php echo SITE_NAME; ?></h2>
                <p class="text-sm text-gray-300">Admin Panel</p>
            </div>
        </div>
        
        <nav class="space-y-2">
            <a href="index.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white hover:bg-opacity-10 transition duration-300 <?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'bg-white bg-opacity-20' : ''; ?>">
                <i class="fas fa-chart-line w-5"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="products.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white hover:bg-opacity-10 transition duration-300 <?php echo basename($_SERVER['PHP_SELF']) === 'products.php' ? 'bg-white bg-opacity-20' : ''; ?>">
                <i class="fas fa-box w-5"></i>
                <span>Products</span>
            </a>
            
            <a href="orders.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white hover:bg-opacity-10 transition duration-300 <?php echo basename($_SERVER['PHP_SELF']) === 'orders.php' ? 'bg-white bg-opacity-20' : ''; ?>">
                <i class="fas fa-shopping-cart w-5"></i>
                <span>Orders</span>
            </a>
            
            <a href="<?php echo SITE_URL; ?>/index.php" target="_blank" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white hover:bg-opacity-10 transition duration-300">
                <i class="fas fa-external-link-alt w-5"></i>
                <span>View Website</span>
            </a>
            
            <a href="logout.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-red-500 hover:bg-opacity-20 transition duration-300 mt-4">
                <i class="fas fa-sign-out-alt w-5"></i>
                <span>Logout</span>
            </a>
        </nav>
    </div>
</aside>

<!-- Mobile Sidebar Overlay -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

<!-- Mobile Sidebar -->
<aside id="mobileSidebar" class="fixed inset-y-0 left-0 bg-gradient-to-b from-primary to-purple-700 text-white w-64 transform -translate-x-full transition-transform duration-300 z-50 lg:hidden">
    <div class="p-6">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-3">
                <i class="fas fa-crown text-3xl text-secondary"></i>
                <div>
                    <h2 class="text-xl font-bold"><?php echo SITE_NAME; ?></h2>
                    <p class="text-sm text-gray-300">Admin Panel</p>
                </div>
            </div>
            <button onclick="toggleSidebar()" class="text-white hover:text-gray-300">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <nav class="space-y-2">
            <a href="index.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white hover:bg-opacity-10 transition duration-300">
                <i class="fas fa-chart-line w-5"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="products.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white hover:bg-opacity-10 transition duration-300">
                <i class="fas fa-box w-5"></i>
                <span>Products</span>
            </a>
            
            <a href="orders.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white hover:bg-opacity-10 transition duration-300">
                <i class="fas fa-shopping-cart w-5"></i>
                <span>Orders</span>
            </a>
            
            <a href="<?php echo SITE_URL; ?>/index.php" target="_blank" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white hover:bg-opacity-10 transition duration-300">
                <i class="fas fa-external-link-alt w-5"></i>
                <span>View Website</span>
            </a>
            
            <a href="logout.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-red-500 hover:bg-opacity-20 transition duration-300 mt-4">
                <i class="fas fa-sign-out-alt w-5"></i>
                <span>Logout</span>
            </a>
        </nav>
    </div>
</aside>

<script>
function toggleSidebar() {
    const mobileSidebar = document.getElementById('mobileSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    mobileSidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}

function toggleUserMenu() {
    const userMenu = document.getElementById('userMenu');
    userMenu.classList.toggle('hidden');
}

// Close user menu when clicking outside
document.addEventListener('click', function(event) {
    const userMenu = document.getElementById('userMenu');
    const userButton = event.target.closest('button[onclick="toggleUserMenu()"]');
    
    if (!userButton && !userMenu.contains(event.target)) {
        userMenu.classList.add('hidden');
    }
});
</script>
