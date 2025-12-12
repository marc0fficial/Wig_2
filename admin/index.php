<?php
$pageTitle = "Dashboard";
include 'includes/header.php';

// Get statistics
$totalProducts = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$totalOrders = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
$pendingOrders = $conn->query("SELECT COUNT(*) as count FROM orders WHERE order_status = 'pending'")->fetch_assoc()['count'];
$completedOrders = $conn->query("SELECT COUNT(*) as count FROM orders WHERE payment_status = 'completed'")->fetch_assoc()['count'];

// Get total revenue
$revenueResult = $conn->query("SELECT SUM(total_amount) as total FROM orders WHERE payment_status = 'completed'");
$totalRevenue = $revenueResult->fetch_assoc()['total'] ?? 0;

// Get recent orders
$recentOrders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);

// Get low stock products
$lowStockProducts = $conn->query("SELECT * FROM products WHERE stock < 5 ORDER BY stock ASC LIMIT 5")->fetch_all(MYSQLI_ASSOC);
?>

<!-- Dashboard Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Revenue -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm mb-1">Total Revenue</p>
                <h3 class="text-3xl font-bold"><?php echo formatPrice($totalRevenue); ?></h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-dollar-sign text-3xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Total Orders -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm mb-1">Total Orders</p>
                <h3 class="text-3xl font-bold"><?php echo $totalOrders; ?></h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-shopping-cart text-3xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Pending Orders -->
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm mb-1">Pending Orders</p>
                <h3 class="text-3xl font-bold"><?php echo $pendingOrders; ?></h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-clock text-3xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Total Products -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm mb-1">Total Products</p>
                <h3 class="text-3xl font-bold"><?php echo $totalProducts; ?></h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-box text-3xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">Recent Orders</h2>
            <a href="orders.php" class="text-primary hover:text-purple-700 text-sm font-semibold">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        <?php if (count($recentOrders) > 0): ?>
        <div class="space-y-4">
            <?php foreach ($recentOrders as $order): ?>
            <div class="border border-gray-200 rounded-lg p-4 hover:border-primary transition duration-300">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-semibold text-gray-900">Order #<?php echo $order['id']; ?></span>
                    <span class="text-sm text-gray-600"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars($order['customer_name']); ?></p>
                        <p class="text-sm font-semibold text-primary"><?php echo formatPrice($order['total_amount']); ?></p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <?php if ($order['payment_status'] === 'completed'): ?>
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Paid</span>
                        <?php else: ?>
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Pending</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="text-gray-600 text-center py-8">No orders yet</p>
        <?php endif; ?>
    </div>
    
    <!-- Low Stock Products -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">Low Stock Alert</h2>
            <a href="products.php" class="text-primary hover:text-purple-700 text-sm font-semibold">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        <?php if (count($lowStockProducts) > 0): ?>
        <div class="space-y-4">
            <?php foreach ($lowStockProducts as $product): ?>
            <div class="border border-gray-200 rounded-lg p-4 hover:border-primary transition duration-300">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($product['name']); ?></p>
                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars($product['category']); ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Stock</p>
                        <p class="text-lg font-bold <?php echo $product['stock'] < 3 ? 'text-red-600' : 'text-yellow-600'; ?>">
                            <?php echo $product['stock']; ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="text-gray-600 text-center py-8">All products are well stocked</p>
        <?php endif; ?>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-8 bg-white rounded-lg shadow-lg p-6">
    <h2 class="text-xl font-bold text-gray-900 mb-6">Quick Actions</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="products.php?action=add" class="flex items-center justify-center space-x-3 bg-primary hover:bg-purple-700 text-white px-6 py-4 rounded-lg transition duration-300">
            <i class="fas fa-plus-circle text-xl"></i>
            <span class="font-semibold">Add New Product</span>
        </a>
        
        <a href="orders.php" class="flex items-center justify-center space-x-3 bg-secondary hover:bg-orange-600 text-white px-6 py-4 rounded-lg transition duration-300">
            <i class="fas fa-list text-xl"></i>
            <span class="font-semibold">View All Orders</span>
        </a>
        
        <a href="<?php echo SITE_URL; ?>/index.php" target="_blank" class="flex items-center justify-center space-x-3 bg-gray-700 hover:bg-gray-800 text-white px-6 py-4 rounded-lg transition duration-300">
            <i class="fas fa-external-link-alt text-xl"></i>
            <span class="font-semibold">View Website</span>
        </a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
