<?php
$pageTitle = 'BiteBuddy - FoodOS';
$activePage = 'home';
$basePath = '';
$extraCss = ['Customer/assets/css/browseResturants.css'];
include __DIR__ . '/dirCommon/header.php';
?>

    <main class="page-wrap">
        <section class="page-title">
            <div>
                <h1>Welcome to BiteBuddy</h1>
                <p>Order food, manage restaurants, deliver orders, and run the platform from one project.</p>
            </div>
        </section>

        <section class="filters" aria-label="Project areas">
            <a class="login-link" href="Customer/view/browseResturants.php">Browse Restaurants</a>
            <a class="login-link" href="dirCommon/login.html">Login</a>
            <a class="login-link" href="restaurantManager/view/dashboard.php">Manager Dashboard</a>
            <a class="login-link" href="deliveryAgent/view/dashboard.html">Agent Dashboard</a>
            <a class="login-link" href="admin/view/adminDashboard.php">Admin Dashboard</a>
        </section>
    </main>

<?php include __DIR__ . '/dirCommon/footer.php'; ?>
