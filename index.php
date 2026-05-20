<?php
$pageTitle = 'BiteBuddy - FoodOS';
$activePage = 'home';
$basePath = '';
$extraCss = ['dirCommon/assets/css/landing.css'];
include __DIR__ . '/dirCommon/header.php';
$landingRole = $_SESSION['user_role'] ?? '';
$dashboardHref = 'Customer/view/browseResturants.php';
$dashboardLabel = 'Browse Restaurants';

if ($landingRole === 'manager') {
    $dashboardHref = 'restaurantManager/view/dashboard.php';
    $dashboardLabel = 'Open Manager Dashboard';
} elseif ($landingRole === 'agent') {
    $dashboardHref = 'deliveryAgent/view/dashboard.php';
    $dashboardLabel = 'Open Delivery Dashboard';
} elseif ($landingRole === 'admin') {
    $dashboardHref = 'admin/view/adminDashboard.php';
    $dashboardLabel = 'Open Admin Dashboard';
}
?>

    <main>
        <section class="landing-hero">
            <div class="page-wrap hero-grid">
                <div class="hero-copy">
                    <span class="eyebrow">Online food ordering system</span>
                    <h1>BiteBuddy FoodOS</h1>
                    <p>Order fresh meals from trusted local restaurants and track everything from checkout to delivery.</p>

                    <div class="hero-actions">
                        <a class="primary-action" href="<?php echo htmlspecialchars($dashboardHref); ?>"><?php echo htmlspecialchars($dashboardLabel); ?></a>
                        <?php if (empty($_SESSION['user_id'])): ?>
                            <a class="secondary-action" href="dirCommon/login.html">Login or Register</a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="hero-panel" aria-label="Order preview">
                    <div class="order-card active">
                        <img src="Customer/assets/images/burger-culture.svg" alt="Burger Culture">
                        <div>
                            <strong>Burger Culture</strong>
                            <span>Ready in 25 min</span>
                        </div>
                        <b>৳470</b>
                    </div>
                    <div class="route-card">
                        <span>Restaurant</span>
                        <div></div>
                        <span>Customer</span>
                    </div>
                    <div class="order-card">
                        <img src="Customer/assets/images/sakura-sushi.svg" alt="Sakura Sushi">
                        <div>
                            <strong>Sakura Sushi</strong>
                            <span>Accepted by rider</span>
                        </div>
                        <b>৳850</b>
                    </div>
                </div>
            </div>
        </section>

        <section class="page-wrap quick-stats" aria-label="Platform summary">
            <div><strong>Fast</strong><span>Simple ordering</span></div>
            <div><strong>Local</strong><span>Nearby restaurants</span></div>
            <div><strong>Fresh</strong><span>Prepared meals</span></div>
            <div><strong>Clear</strong><span>Order tracking</span></div>
        </section>

        <section class="page-wrap role-section">
            <div class="section-heading">
                <span class="eyebrow">For customers</span>
                <h2>A cleaner way to order food</h2>
            </div>

            <div class="role-grid">
                <a class="role-card" href="Customer/view/browseResturants.php">
                    <span>01</span>
                    <h3>Browse</h3>
                    <p>Explore approved restaurants and available menu items.</p>
                </a>
                <a class="role-card" href="Customer/view/checkout.php">
                    <span>02</span>
                    <h3>Checkout</h3>
                    <p>Review your cart, delivery address, and payment method.</p>
                </a>
                <a class="role-card" href="Customer/view/orders.php">
                    <span>03</span>
                    <h3>Track</h3>
                    <p>Follow order status from restaurant acceptance to delivery.</p>
                </a>
                <a class="role-card" href="Customer/view/orders.php">
                    <span>04</span>
                    <h3>Review</h3>
                    <p>Rate delivered orders and help restaurants improve.</p>
                </a>
            </div>
        </section>

        <section class="featured-band">
            <div class="page-wrap featured-grid">
                <div>
                    <span class="eyebrow">Popular picks</span>
                    <h2>Find something good without extra noise.</h2>
                    <p>BiteBuddy keeps the customer side focused on food, cart, orders, and reviews. Team dashboards stay behind the right login.</p>
                </div>

                <div class="restaurant-strip">
                    <img src="Customer/assets/images/luigis-pizzeria.svg" alt="Luigi's Pizzeria">
                    <img src="Customer/assets/images/green-bowl.svg" alt="Green Bowl">
                    <img src="Customer/assets/images/taco-fiesta.svg" alt="Taco Fiesta">
                </div>
            </div>
        </section>
    </main>

<?php include __DIR__ . '/dirCommon/footer.php'; ?>
