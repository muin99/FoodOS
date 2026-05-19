<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../../dirCommon/dbconnect.php';
include __DIR__ . '/../model/customerModel.php';

if (($_SESSION['user_role'] ?? '') !== 'customer' || !isset($_SESSION['user_id'])) {
    header('Location: ../../dirCommon/login.html');
    exit;
}

$addresses = getCustomerAddresses($conn, (int)$_SESSION['user_id']);

$pageTitle = 'BiteBuddy - Checkout';
$activePage = 'browse';
$basePath = '../../';
$extraCss = ['../css/browseResturants.css'];
include __DIR__ . '/../../dirCommon/header.php';
?>

<main class="page-wrap customer-page">
    <section class="customer-hero compact">
        <div>
            <span class="eyebrow">Checkout</span>
            <h1>Confirm your order</h1>
            <p>Review your cart, add an address, and place the order.</p>
        </div>
    </section>

    <section class="checkout-layout">
        <div class="checkout-card">
            <h2>Delivery details</h2>
            <form id="checkoutForm">
                <label>
                    <span>Saved address</span>
                    <select id="savedAddress">
                        <option value="">Use a new address</option>
                        <?php foreach ($addresses as $address): ?>
                            <option value="<?php echo htmlspecialchars($address['address_line']); ?>" data-city="<?php echo htmlspecialchars($address['city']); ?>">
                                <?php echo htmlspecialchars($address['label'] . ' - ' . $address['address_line']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>
                    <span>Delivery address</span>
                    <textarea id="deliveryAddress" required placeholder="House, road, area"></textarea>
                </label>
                <label>
                    <span>City</span>
                    <input id="city" value="Dhaka" required>
                </label>
                <label>
                    <span>Payment method</span>
                    <select id="paymentMethod">
                        <option value="Cash">Cash</option>
                        <option value="Card">Card</option>
                    </select>
                </label>
                <button type="submit">Place Order</button>
            </form>
            <p id="checkoutMessage" class="form-message"></p>
        </div>

        <aside class="cart-panel">
            <h2>Order summary</h2>
            <div id="cartItems" class="cart-items"></div>
            <div class="cart-total">
                <span>Subtotal</span>
                <strong id="cartSubtotal">৳0.00</strong>
            </div>
            <div class="cart-total">
                <span>Delivery</span>
                <strong>৳50.00</strong>
            </div>
            <div class="cart-total grand">
                <span>Total</span>
                <strong id="cartGrandTotal">৳50.00</strong>
            </div>
        </aside>
    </section>
</main>

<script src="../assets/js/customerCart.js"></script>
<script src="../assets/js/checkout.js"></script>
<?php include __DIR__ . '/../../dirCommon/footer.php'; ?>
 
