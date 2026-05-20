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
$roleHome = $basePath . 'Customer/view/browseResturants.php';
$profileHref = $basePath . 'Customer/view/profile.php';
$logoutHref = $basePath . 'Customer/controller/logout.php';
$roleLabel = 'Customer';

if ($userRole === 'manager') {
    $roleHome = $basePath . 'restaurantManager/view/dashboard.php';
    $profileHref = $basePath . 'restaurantManager/view/profile.php';
    $logoutHref = $basePath . 'restaurantManager/controller/logout.php';
    $roleLabel = 'Restaurant Manager';
} elseif ($userRole === 'agent') {
    $roleHome = $basePath . 'deliveryAgent/view/dashboard.php';
    $profileHref = $basePath . 'deliveryAgent/view/dashboard.php';
    $logoutHref = $basePath . 'deliveryAgent/controller/logout.php';
    $roleLabel = 'Delivery Agent';
} elseif ($userRole === 'admin') {
    $roleHome = $basePath . 'admin/view/adminDashboard.php';
    $profileHref = $basePath . 'admin/view/adminDashboard.php';
    $logoutHref = $basePath . 'admin/controller/logout.php';
    $roleLabel = 'Admin';
}
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
                <?php if (!$loggedIn || $userRole === 'customer'): ?>
                    <a class="<?php echo $activePage === 'browse' ? 'active' : ''; ?>" href="<?php echo $basePath; ?>Customer/view/browseResturants.php">Restaurants</a>
                <?php endif; ?>
                <?php if ($loggedIn && $userRole === 'customer'): ?>
                    <a class="<?php echo $activePage === 'orders' ? 'active' : ''; ?>" href="<?php echo $basePath; ?>Customer/view/orders.php">My Orders</a>
                <?php endif; ?>
                <?php if ($loggedIn && $userRole === 'manager'): ?>
                    <a class="<?php echo $activePage === 'manager' ? 'active' : ''; ?>" href="<?php echo $basePath; ?>restaurantManager/view/dashboard.php">Dashboard</a>
                    <a href="<?php echo $basePath; ?>restaurantManager/view/orders.php">Orders</a>
                    <a href="<?php echo $basePath; ?>restaurantManager/view/menu.php">Menu</a>
                    <a href="<?php echo $basePath; ?>restaurantManager/view/insights.php">Insights</a>
                <?php endif; ?>
                <?php if ($loggedIn && $userRole === 'agent'): ?>
                    <a class="<?php echo $activePage === 'delivery' ? 'active' : ''; ?>" href="<?php echo $basePath; ?>deliveryAgent/view/dashboard.php">Delivery Dashboard</a>
                <?php endif; ?>
                <?php if ($loggedIn && $userRole === 'admin'): ?>
                    <a class="<?php echo $activePage === 'admin' ? 'active' : ''; ?>" href="<?php echo $basePath; ?>admin/view/adminDashboard.php">Admin Dashboard</a>
                    <a href="<?php echo $basePath; ?>admin/view/restaurants.php">Restaurants</a>
                <?php endif; ?>
            </nav>

            <div class="header-actions">
                <?php if ($loggedIn && $userRole === 'customer'): ?>
                    <a class="cart-link" href="<?php echo $basePath; ?>Customer/view/checkout.php">Cart</a>
                    <a class="user-chip" href="<?php echo $basePath; ?>Customer/view/profile.php">
                        <span><?php echo htmlspecialchars(substr($userName, 0, 1)); ?></span>
                        <strong><?php echo htmlspecialchars($userName); ?></strong>
                    </a>
                    <a class="login-link" href="<?php echo htmlspecialchars($logoutHref); ?>">Logout</a>
                <?php elseif ($loggedIn): ?>
                    <a class="user-chip" href="<?php echo htmlspecialchars($profileHref); ?>" title="<?php echo htmlspecialchars($roleLabel); ?>">
                        <span><?php echo htmlspecialchars(substr($userName, 0, 1)); ?></span>
                        <strong><?php echo htmlspecialchars($userName); ?></strong>
                    </a>
                    <a class="login-link" href="<?php echo htmlspecialchars($logoutHref); ?>">Logout</a>
                <?php else: ?>
                    <a class="cart-link" href="<?php echo $basePath; ?>Customer/view/browseResturants.php">Browse Food</a>
                    <a class="login-link" href="<?php echo $basePath; ?>dirCommon/login.html">Login / Register</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
