<?php
$pageTitle = 'BiteBuddy - FoodOS';
$activePage = 'home';
$basePath = '';
$extraCss = ['dirCommon/assets/css/landing.css'];
include __DIR__ . '/dirCommon/header.php';
?>

    <main>
        <section class="landing-hero">
            <div class="page-wrap hero-grid">
                <div class="hero-copy">
                    <span class="eyebrow">Online food ordering system</span>
                    <h1>BiteBuddy FoodOS</h1>
                    <p>Browse restaurants, place orders, manage menus, assign deliveries, and monitor the platform from one simple PHP project.</p>

                    <div class="hero-actions">
                        <a class="primary-action" href="Customer/view/browseResturants.php">Browse Restaurants</a>
                        <a class="secondary-action" href="dirCommon/login.html">Login or Register</a>
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
            <div><strong>4</strong><span>User roles</span></div>
            <div><strong>12+</strong><span>Demo orders</span></div>
            <div><strong>3</strong><span>Restaurant samples</span></div>
            <div><strong>Basic</strong><span>PHP + MySQL</span></div>
        </section>

        <section class="page-wrap role-section">
            <div class="section-heading">
                <span class="eyebrow">Work areas</span>
                <h2>Everything has a clear dashboard</h2>
            </div>

            <div class="role-grid">
                <a class="role-card" href="Customer/view/browseResturants.php">
                    <span>01</span>
                    <h3>Customer</h3>
                    <p>Browse restaurants, discover food, and start an order.</p>
                </a>
                <a class="role-card" href="restaurantManager/view/dashboard.php">
                    <span>02</span>
                    <h3>Restaurant Manager</h3>
                    <p>Track orders, update menu items, and view restaurant insights.</p>
                </a>
                <a class="role-card" href="deliveryAgent/view/dashboard.html">
                    <span>03</span>
                    <h3>Delivery Agent</h3>
                    <p>Check available orders, manage status, and review earnings.</p>
                </a>
                <a class="role-card" href="admin/view/adminDashboard.php">
                    <span>04</span>
                    <h3>Admin</h3>
                    <p>Review users, restaurants, platform totals, and approvals.</p>
                </a>
            </div>
        </section>

        <section class="featured-band">
            <div class="page-wrap featured-grid">
                <div>
                    <span class="eyebrow">Demo restaurants</span>
                    <h2>Import the demo SQL and start clicking around.</h2>
                    <p>The demo data includes customers, managers, agents, restaurants, menus, orders, reviews, addresses, saved restaurants, and complaints.</p>
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
