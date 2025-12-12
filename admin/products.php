<?php
$pageTitle = "Products Management";
include 'includes/header.php';

// Handle product actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action === 'add') {
            $name = sanitize($_POST['name']);
            $description = sanitize($_POST['description']);
            $price = floatval($_POST['price']);
            $category = sanitize($_POST['category']);
            $stock = intval($_POST['stock']);
            $featured = isset($_POST['featured']) ? 1 : 0;
            
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, category, stock, featured) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdsii", $name, $description, $price, $category, $stock, $featured);
            
            if ($stmt->execute()) {
                $success = "Product added successfully!";
            } else {
                $error = "Failed to add product.";
            }
        } elseif ($action === 'edit') {
            $id = intval($_POST['id']);
            $name = sanitize($_POST['name']);
            $description = sanitize($_POST['description']);
            $price = floatval($_POST['price']);
            $category = sanitize($_POST['category']);
            $stock = intval($_POST['stock']);
            $featured = isset($_POST['featured']) ? 1 : 0;
            
            $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, category=?, stock=?, featured=? WHERE id=?");
            $stmt->bind_param("ssdsiii", $name, $description, $price, $category, $stock, $featured, $id);
            
            if ($stmt->execute()) {
                $success = "Product updated successfully!";
            } else {
                $error = "Failed to update product.";
            }
        } elseif ($action === 'delete') {
            $id = intval($_POST['id']);
            $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $success = "Product deleted successfully!";
            } else {
                $error = "Failed to delete product.";
            }
        }
    }
}

// Get all products
$products = $conn->query("SELECT * FROM products ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);

// Get product for editing
$editProduct = null;
if (isset($_GET['edit'])) {
    $editId = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $editProduct = $stmt->get_result()->fetch_assoc();
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

<!-- Add/Edit Product Form -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">
        <?php echo $editProduct ? 'Edit Product' : 'Add New Product'; ?>
    </h2>
    
    <form method="POST" action="" class="space-y-4">
        <input type="hidden" name="action" value="<?php echo $editProduct ? 'edit' : 'add'; ?>">
        <?php if ($editProduct): ?>
        <input type="hidden" name="id" value="<?php echo $editProduct['id']; ?>">
        <?php endif; ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 font-medium mb-2">Product Name *</label>
                <input type="text" name="name" required 
                       value="<?php echo $editProduct ? htmlspecialchars($editProduct['name']) : ''; ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            
            <div>
                <label class="block text-gray-700 font-medium mb-2">Category *</label>
                <select name="category" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">Select Category</option>
                    <option value="Straight" <?php echo ($editProduct && $editProduct['category'] === 'Straight') ? 'selected' : ''; ?>>Straight</option>
                    <option value="Curly" <?php echo ($editProduct && $editProduct['category'] === 'Curly') ? 'selected' : ''; ?>>Curly</option>
                    <option value="Wavy" <?php echo ($editProduct && $editProduct['category'] === 'Wavy') ? 'selected' : ''; ?>>Wavy</option>
                    <option value="Bob" <?php echo ($editProduct && $editProduct['category'] === 'Bob') ? 'selected' : ''; ?>>Bob</option>
                    <option value="Short" <?php echo ($editProduct && $editProduct['category'] === 'Short') ? 'selected' : ''; ?>>Short</option>
                    <option value="Kinky" <?php echo ($editProduct && $editProduct['category'] === 'Kinky') ? 'selected' : ''; ?>>Kinky</option>
                </select>
            </div>
            
            <div>
                <label class="block text-gray-700 font-medium mb-2">Price ($) *</label>
                <input type="number" name="price" step="0.01" required 
                       value="<?php echo $editProduct ? $editProduct['price'] : ''; ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            
            <div>
                <label class="block text-gray-700 font-medium mb-2">Stock Quantity *</label>
                <input type="number" name="stock" required 
                       value="<?php echo $editProduct ? $editProduct['stock'] : ''; ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
        </div>
        
        <div>
            <label class="block text-gray-700 font-medium mb-2">Description *</label>
            <textarea name="description" rows="4" required 
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"><?php echo $editProduct ? htmlspecialchars($editProduct['description']) : ''; ?></textarea>
        </div>
        
        <div>
            <label class="flex items-center cursor-pointer">
                <input type="checkbox" name="featured" 
                       <?php echo ($editProduct && $editProduct['featured']) ? 'checked' : ''; ?>
                       class="mr-2">
                <span class="text-gray-700">Featured Product</span>
            </label>
        </div>
        
        <div class="flex space-x-4">
            <button type="submit" 
                    class="bg-primary hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-300">
                <i class="fas fa-save mr-2"></i><?php echo $editProduct ? 'Update Product' : 'Add Product'; ?>
            </button>
            
            <?php if ($editProduct): ?>
            <a href="products.php" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold transition duration-300">
                <i class="fas fa-times mr-2"></i>Cancel
            </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Products List -->
<div class="bg-white rounded-lg shadow-lg p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">All Products</h2>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">ID</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Category</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Price</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Stock</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Featured</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm"><?php echo $product['id']; ?></td>
                    <td class="px-4 py-3 text-sm font-medium"><?php echo htmlspecialchars($product['name']); ?></td>
                    <td class="px-4 py-3 text-sm"><?php echo htmlspecialchars($product['category']); ?></td>
                    <td class="px-4 py-3 text-sm font-semibold text-primary"><?php echo formatPrice($product['price']); ?></td>
                    <td class="px-4 py-3 text-sm">
                        <span class="<?php echo $product['stock'] < 5 ? 'text-red-600 font-bold' : 'text-gray-700'; ?>">
                            <?php echo $product['stock']; ?>
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <?php if ($product['featured']): ?>
                        <span class="px-2 py-1 bg-secondary text-white text-xs rounded-full">Yes</span>
                        <?php else: ?>
                        <span class="px-2 py-1 bg-gray-200 text-gray-700 text-xs rounded-full">No</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <div class="flex space-x-2">
                            <a href="?edit=<?php echo $product['id']; ?>" 
                               class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
