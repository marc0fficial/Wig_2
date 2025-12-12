<?php
$pageTitle = "Payment";
include 'includes/header.php';

// Get order ID
$orderId = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if (!$orderId) {
    header('Location: index.php');
    exit();
}

// Get order details
$order = getOrderById($orderId);
if (!$order) {
    header('Location: index.php');
    exit();
}

$orderItems = getOrderItems($orderId);
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-primary to-purple-600 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold mb-2">Complete Payment</h1>
        <p class="text-gray-100">Order #<?php echo $orderId; ?></p>
    </div>
</section>

<!-- Payment Section -->
<section class="py-12 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if ($order['payment_method'] === 'airtel_money'): ?>
        <!-- Airtel Money Payment -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <i class="fas fa-mobile-alt text-primary text-6xl mb-4"></i>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Airtel Money Payment</h2>
                <p class="text-gray-600">Complete your payment using Airtel Money</p>
            </div>
            
            <div class="bg-primary bg-opacity-10 rounded-lg p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Payment Instructions:</h3>
                <ol class="space-y-3 text-gray-700">
                    <li class="flex items-start">
                        <span class="bg-primary text-white rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0 text-sm">1</span>
                        <span>Dial <strong>*182*7*1#</strong> on your Airtel phone</span>
                    </li>
                    <li class="flex items-start">
                        <span class="bg-primary text-white rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0 text-sm">2</span>
                        <span>Select "Send Money"</span>
                    </li>
                    <li class="flex items-start">
                        <span class="bg-primary text-white rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0 text-sm">3</span>
                        <span>Enter the merchant number: <strong class="text-primary"><?php echo AIRTEL_MONEY_NUMBER; ?></strong></span>
                    </li>
                    <li class="flex items-start">
                        <span class="bg-primary text-white rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0 text-sm">4</span>
                        <span>Enter amount: <strong class="text-primary"><?php echo formatPrice($order['total_amount']); ?></strong></span>
                    </li>
                    <li class="flex items-start">
                        <span class="bg-primary text-white rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0 text-sm">5</span>
                        <span>Enter your PIN to confirm</span>
                    </li>
                    <li class="flex items-start">
                        <span class="bg-primary text-white rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0 text-sm">6</span>
                        <span>You will receive a confirmation SMS with a transaction ID</span>
                    </li>
                    <li class="flex items-start">
                        <span class="bg-primary text-white rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0 text-sm">7</span>
                        <span>Enter the transaction ID below to confirm your payment</span>
                    </li>
                </ol>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <div class="grid grid-cols-2 gap-4 text-center">
                    <div>
                        <p class="text-gray-600 mb-1">Merchant Number</p>
                        <p class="text-2xl font-bold text-primary"><?php echo AIRTEL_MONEY_NUMBER; ?></p>
                    </div>
                    <div>
                        <p class="text-gray-600 mb-1">Amount to Pay</p>
                        <p class="text-2xl font-bold text-primary"><?php echo formatPrice($order['total_amount']); ?></p>
                    </div>
                </div>
            </div>
            
            <form id="airtelPaymentForm" class="space-y-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Transaction ID *</label>
                    <input type="text" name="transaction_id" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                           placeholder="Enter the transaction ID from your SMS">
                    <p class="text-sm text-gray-600 mt-1">Example: MP240123.1234.A12345</p>
                </div>
                
                <button type="submit" 
                        class="w-full bg-primary hover:bg-purple-700 text-white px-8 py-4 rounded-lg font-semibold transition duration-300 shadow-lg">
                    <i class="fas fa-check-circle mr-2"></i> Confirm Payment
                </button>
            </form>
        </div>
        
        <?php elseif ($order['payment_method'] === 'card'): ?>
        <!-- Card Payment (Stripe) -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <i class="fab fa-cc-visa text-blue-600 text-6xl mb-4"></i>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Card Payment</h2>
                <p class="text-gray-600">Pay securely with your credit or debit card</p>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <div class="text-center">
                    <p class="text-gray-600 mb-1">Amount to Pay</p>
                    <p class="text-3xl font-bold text-primary"><?php echo formatPrice($order['total_amount']); ?></p>
                </div>
            </div>
            
            <form id="cardPaymentForm" class="space-y-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Card Number *</label>
                    <div id="card-number" class="px-4 py-3 border border-gray-300 rounded-lg"></div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Expiry Date *</label>
                        <div id="card-expiry" class="px-4 py-3 border border-gray-300 rounded-lg"></div>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">CVC *</label>
                        <div id="card-cvc" class="px-4 py-3 border border-gray-300 rounded-lg"></div>
                    </div>
                </div>
                
                <div id="card-errors" class="text-red-600 text-sm"></div>
                
                <button type="submit" id="card-button"
                        class="w-full bg-primary hover:bg-purple-700 text-white px-8 py-4 rounded-lg font-semibold transition duration-300 shadow-lg">
                    <i class="fas fa-lock mr-2"></i> Pay <?php echo formatPrice($order['total_amount']); ?>
                </button>
                
                <div class="text-center text-sm text-gray-600">
                    <i class="fas fa-shield-alt mr-1"></i>
                    Your payment is secured by Stripe
                </div>
            </form>
        </div>
        <?php endif; ?>
        
        <!-- Order Summary -->
        <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Order Summary</h3>
            <div class="space-y-3">
                <?php foreach ($orderItems as $item): ?>
                <div class="flex justify-between text-gray-700">
                    <span><?php echo htmlspecialchars($item['product_name']); ?> x <?php echo $item['quantity']; ?></span>
                    <span class="font-semibold"><?php echo formatPrice($item['subtotal']); ?></span>
                </div>
                <?php endforeach; ?>
                <div class="border-t border-gray-200 pt-3">
                    <div class="flex justify-between text-xl font-bold text-gray-900">
                        <span>Total</span>
                        <span class="text-primary"><?php echo formatPrice($order['total_amount']); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stripe JS -->
<script src="https://js.stripe.com/v3/"></script>

<script>
<?php if ($order['payment_method'] === 'airtel_money'): ?>
// Airtel Money Payment
document.getElementById('airtelPaymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const transactionId = this.querySelector('[name="transaction_id"]').value;
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Verifying...';
    submitBtn.disabled = true;
    
    fetch('<?php echo SITE_URL; ?>/api/payment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            order_id: <?php echo $orderId; ?>,
            payment_method: 'airtel_money',
            transaction_id: transactionId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = 'order-confirmation.php?order_id=<?php echo $orderId; ?>';
        } else {
            alert('Payment verification failed: ' + (data.message || 'Unknown error'));
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error processing payment. Please try again.');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

<?php elseif ($order['payment_method'] === 'card'): ?>
// Stripe Card Payment
const stripe = Stripe('<?php echo STRIPE_PUBLISHABLE_KEY; ?>');
const elements = stripe.elements();

const cardNumber = elements.create('cardNumber');
cardNumber.mount('#card-number');

const cardExpiry = elements.create('cardExpiry');
cardExpiry.mount('#card-expiry');

const cardCvc = elements.create('cardCvc');
cardCvc.mount('#card-cvc');

const cardErrors = document.getElementById('card-errors');

cardNumber.on('change', function(event) {
    if (event.error) {
        cardErrors.textContent = event.error.message;
    } else {
        cardErrors.textContent = '';
    }
});

document.getElementById('cardPaymentForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('card-button');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
    submitBtn.disabled = true;
    
    try {
        const {token, error} = await stripe.createToken(cardNumber);
        
        if (error) {
            cardErrors.textContent = error.message;
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            return;
        }
        
        const response = await fetch('<?php echo SITE_URL; ?>/api/payment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                order_id: <?php echo $orderId; ?>,
                payment_method: 'card',
                stripe_token: token.id
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            window.location.href = 'order-confirmation.php?order_id=<?php echo $orderId; ?>';
        } else {
            cardErrors.textContent = data.message || 'Payment failed. Please try again.';
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    } catch (error) {
        console.error('Error:', error);
        cardErrors.textContent = 'Error processing payment. Please try again.';
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
});
<?php endif; ?>
</script>

<?php include 'includes/footer.php'; ?>
