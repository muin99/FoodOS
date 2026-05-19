<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = $pageTitle ?? 'BiteBuddy';
$activePage = $activePage ?? '';
$basePath = $basePath ?? '';
$extraCss = $extraCss ?? [];
$loggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? $_SESSION['name'] ?? '';
$userRole = $_SESSION['user_role'] ?? $_SESSION['role'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="<?php echo $basePath; ?>dirCommon/assets/css/common.css">
    <?php foreach ($extraCss as $cssFile): ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars($cssFile); ?>">
    <?php endforeach; ?>
</head>
<body>
    <header class="site-header">
        <div class="page-wrap header-inner">
            <a class="brand" href="<?php echo $basePath; ?>index.php">
                <span class="brand-icon">B</span>
                <span class="brand-copy">
                    <span>BiteBuddy</span>
                    <small>FoodOS</small>
                </span>
            </a>

            <nav class="main-nav" aria-label="Main navigation">
                <a class="<?php echo $activePage === 'home' ? 'active' : ''; ?>" href="<?php echo $basePath; ?>index.php">Home</a>
                <a class="<?php echo $activePage === 'browse' ? 'active' : ''; ?>" href="<?php echo $basePath; ?>Customer/view/browseResturants.php">Restaurants</a>
                <?php if ($userRole === 'customer'): ?>
                    <a class="<?php echo $activePage === 'orders' ? 'active' : ''; ?>" href="<?php echo $basePath; ?>Customer/view/orders.php">My Orders</a>
                <?php endif; ?>
                <a href="<?php echo $basePath; ?>restaurantManager/view/dashboard.php">Manager</a>
                <a href="<?php echo $basePath; ?>deliveryAgent/view/dashboard.php">Delivery</a>
                <a href="<?php echo $basePath; ?>admin/view/adminDashboard.php">Admin</a>
            </nav>

            <div class="header-actions">
                <?php if ($loggedIn && $userRole === 'customer'): ?>
                    <a class="cart-link" href="<?php echo $basePath; ?>Customer/view/checkout.php">Cart</a>
                    <a class="user-chip" href="<?php echo $basePath; ?>Customer/view/profile.php">
                        <span><?php echo htmlspecialchars(substr($userName, 0, 1)); ?></span>
                        <strong><?php echo htmlspecialchars($userName); ?></strong>
                    </a>
                    <a class="login-link" href="<?php echo $basePath; ?>Customer/controller/logout.php">Logout</a>
                <?php elseif ($loggedIn): ?>
                    <a class="user-chip" href="<?php echo $basePath; ?>dirCommon/login.html">
                        <span><?php echo htmlspecialchars(substr($userName, 0, 1)); ?></span>
                        <strong><?php echo htmlspecialchars($userName); ?></strong>
                    </a>
                    <a class="login-link" href="<?php echo $basePath; ?>Customer/controller/logout.php">Logout</a>
                <?php else: ?>
                    <a class="cart-link" href="<?php echo $basePath; ?>Customer/view/browseResturants.php">Browse Food</a>
                    <a class="login-link" href="<?php echo $basePath; ?>dirCommon/login.html">Login / Register</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
