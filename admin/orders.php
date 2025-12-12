<?php
$pageTitle = "Orders Management";
include 'includes/header.php';

// Handle order status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_status') {
        $orderId = intval($_POST['order_id']);
        $orderStatus = sanitize($_POST['order_status']);
        $paymentStatus = sanitize($_POST['payment_status']);
        
        $stmt = $conn->prepare("UPDATE orders SET order_status=?, payment_status=? WHERE id=?");
        $stmt->bind_param("ssi", $orderStatus, $paymentStatus, $orderId);
        
        if ($stmt->execute()) {
            $success = "Order status updated successfully!";
        } else {
            $error = "Failed to update order status.";
        }
    }
}

// Get filter parameters
$statusFilter = isset($_GET['status']) ? sanitize($_GET['status']) : '';
$paymentFilter = isset($_GET['payment']) ? sanitize($_GET['payment']) : '';

// Build query
$query = "SELECT * FROM orders WHERE 1=1";
if ($statusFilter) {
    $query .= " AND order_status = '" . $conn->real_escape_string($statusFilter) . "'";
}
if ($paymentFilter) {
    $query .= " AND payment_status = '" . $conn->real_escape_string($paymentFilter) . "'";
}
$query .= " ORDER BY created_at DESC";

$orders = $conn->query($query)->fetch_all(MYSQLI_ASSOC);

// Get order details for modal
$orderDetails = null;
if (isset($_GET['view'])) {
    $viewId = intval($_GET['view']);
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id=?");
    $stmt->bind_param("i", $viewId);
    $stmt->execute();
    $orderDetails = $stmt->get_result()->fetch_assoc();
    
    if ($orderDetails) {
        $stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id=?");
        $stmt->bind_param("i", $viewId);
        $stmt->execute();
        $orderItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>

<?php if (isset($success)): ?>
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    <i class="fas fa-check-circle mr-2"></i><?php echo $success; ?>
</div>
<?php endif; ?>

<?php if (isset($error)): ?>
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
    <i class="fas fa-exclamation-circle mr-2"></i><?php echo $error; ?>
</div>
<?php endif; ?>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm mb-1">Total Orders</p>
                <h3 class="text-2xl font-bold text-gray-900">
                    <?php echo $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count']; ?>
                </h3>
            </div>
            <i class="fas fa-shopping-cart text-blue-500 text-3xl"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm mb-1">Pending</p>
                <h3 class="text-2xl font-bold text-yellow-600">
                    <?php echo $conn->query("SELECT COUNT(*) as count FROM orders WHERE order_status='pending'")->fetch_assoc()['count']; ?>
                </h3>
            </div>
            <i class="fas fa-clock text-yellow-500 text-3xl"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm mb-1">Processing</p>
                <h3 class="text-2xl font-bold text-blue-600">
                    <?php echo $conn->query("SELECT COUNT(*) as count FROM orders WHERE order_status='processing'")->fetch_assoc()['count']; ?>
                </h3>
            </div>
            <i class="fas fa-cog text-blue-500 text-3xl"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm mb-1">Completed</p>
                <h3 class="text-2xl font-bold text-green-600">
                    <?php echo $conn->query("SELECT COUNT(*) as count FROM orders WHERE order_status='completed'")->fetch_assoc()['count']; ?>
                </h3>
            </div>
            <i class="fas fa-check-circle text-green-500 text-3xl"></i>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4">Filter Orders</h3>
    <form method="GET" action="" class="flex flex-wrap gap-4">
        <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
            <option value="">All Order Status</option>
            <option value="pending" <?php echo $statusFilter === 'pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="processing" <?php echo $statusFilter === 'processing' ? 'selected' : ''; ?>>Processing</option>
            <option value="completed" <?php echo $statusFilter === 'completed' ? 'selected' : ''; ?>>Completed</option>
            <option value="cancelled" <?php echo $statusFilter === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
        </select>
        
        <select name="payment" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
            <option value="">All Payment Status</option>
            <option value="pending" <?php echo $paymentFilter === 'pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="completed" <?php echo $paymentFilter === 'completed' ? 'selected' : ''; ?>>Completed</option>
            <option value="failed" <?php echo $paymentFilter === 'failed' ? 'selected' : ''; ?>>Failed</option>
        </select>
        
        <button type="submit" class="bg-primary hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-300">
            <i class="fas fa-filter mr-2"></i>Apply Filters
        </button>
        
        <a href="orders.php" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold transition duration-300">
            <i class="fas fa-redo mr-2"></i>Reset
        </a>
    </form>
</div>

<!-- Orders List -->
<div class="bg-white rounded-lg shadow-lg p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">All Orders</h2>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Order ID</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Customer</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Total</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Payment</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Order Status</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Payment Status</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($orders) > 0): ?>
                    <?php foreach ($orders as $order): ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-semibold">#<?php echo $order['id']; ?></td>
                        <td class="px-4 py-3 text-sm">
                            <div>
                                <p class="font-medium"><?php echo htmlspecialchars($order['customer_name']); ?></p>
                                <p class="text-gray-600 text-xs"><?php echo htmlspecialchars($order['customer_email']); ?></p>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm font-semibold text-primary"><?php echo formatPrice($order['total_amount']); ?></td>
                        <td class="px-4 py-3 text-sm">
                            <?php if ($order['payment_method'] === 'airtel_money'): ?>
                                <span class="text-xs"><i class="fas fa-mobile-alt text-primary"></i> Airtel Money</span>
                            <?php else: ?>
                                <span class="text-xs"><i class="fab fa-cc-visa text-blue-600"></i> Card</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <?php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'processing' => 'bg-blue-100 text-blue-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800'
                            ];
                            $statusColor = $statusColors[$order['order_status']] ?? 'bg-gray-100 text-gray-800';
                            ?>
                            <span class="px-2 py-1 <?php echo $statusColor; ?> text-xs rounded-full">
                                <?php echo ucfirst($order['order_status']); ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <?php
                            $paymentColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'failed' => 'bg-red-100 text-red-800'
                            ];
                            $paymentColor = $paymentColors[$order['payment_status']] ?? 'bg-gray-100 text-gray-800';
                            ?>
                            <span class="px-2 py-1 <?php echo $paymentColor; ?> text-xs rounded-full">
                                <?php echo ucfirst($order['payment_status']); ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            <?php echo date('M d, Y', strtotime($order['created_at'])); ?>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex space-x-2">
                                <a href="?view=<?php echo $order['id']; ?>" 
                                   class="text-blue-600 hover:text-blue-800" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-600">
                            No orders found
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Order Details Modal -->
<?php if ($orderDetails): ?>
<div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-4xl w-full max-h-screen overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Order #<?php echo $orderDetails['id']; ?></h2>
                <a href="orders.php" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-times text-2xl"></i>
                </a>
            </div>
            
            <!-- Customer Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-3">Customer Information</h3>
                    <div class="space-y-2 text-sm">
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($orderDetails['customer_name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($orderDetails['customer_email']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($orderDetails['customer_phone']); ?></p>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-3">Shipping Address</h3>
                    <p class="text-sm"><?php echo nl2br(htmlspecialchars($orderDetails['shipping_address'])); ?></p>
                </div>
            </div>
            
            <!-- Order Items -->
            <div class="mb-6">
                <h3 class="font-semibold text-gray-900 mb-3">Order Items</h3>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Product</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Quantity</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Price</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orderItems as $item): ?>
                            <tr class="border-t border-gray-200">
                                <td class="px-4 py-2 text-sm"><?php echo htmlspecialchars($item['product_name']); ?></td>
                                <td class="px-4 py-2 text-sm"><?php echo $item['quantity']; ?></td>
                                <td class="px-4 py-2 text-sm"><?php echo formatPrice($item['price']); ?></td>
                                <td class="px-4 py-2 text-sm font-semibold"><?php echo formatPrice($item['subtotal']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <tr class="border-t-2 border-gray-300 bg-gray-50">
                                <td colspan="3" class="px-4 py-2 text-right font-semibold">Total:</td>
                                <td class="px-4 py-2 text-lg font-bold text-primary"><?php echo formatPrice($orderDetails['total_amount']); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Update Status Form -->
            <form method="POST" action="" class="bg-gray-50 rounded-lg p-4">
                <h3 class="font-semibold text-gray-900 mb-4">Update Order Status</h3>
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="order_id" value="<?php echo $orderDetails['id']; ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Order Status</label>
                        <select name="order_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="pending" <?php echo $orderDetails['order_status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="processing" <?php echo $orderDetails['order_status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                            <option value="completed" <?php echo $orderDetails['order_status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="cancelled" <?php echo $orderDetails['order_status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Payment Status</label>
                        <select name="payment_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="pending" <?php echo $orderDetails['payment_status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="completed" <?php echo $orderDetails['payment_status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="failed" <?php echo $orderDetails['payment_status'] === 'failed' ? 'selected' : ''; ?>>Failed</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex space-x-4">
                    <button type="submit" class="bg-primary hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-300">
                        <i class="fas fa-save mr-2"></i>Update Status
                    </button>
                    <a href="orders.php" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold transition duration-300">
                        <i class="fas fa-times mr-2"></i>Close
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
