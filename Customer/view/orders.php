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

$orders = getCustomerOrders($conn, (int)$_SESSION['user_id']);

$pageTitle = 'BiteBuddy - My Orders';
$activePage = 'orders';
$basePath = '../../';
$extraCss = ['../css/browseResturants.css'];
include __DIR__ . '/../../dirCommon/header.php';
?>

<main class="page-wrap customer-page">
    <section class="customer-hero compact">
        <div>
            <span class="eyebrow">Order history</span>
            <h1>My Orders</h1>
            <p>Track recent orders and check your order status.</p>
        </div>
        <a class="outline-action" href="browseResturants.php">Order again</a>
    </section>

    <?php if (count($orders) === 0): ?>
        <div class="empty-state">
            <h3>No orders yet</h3>
            <p>Start by choosing a restaurant from the browse page.</p>
            <a class="primary-action" href="browseResturants.php">Browse Restaurants</a>
        </div>
    <?php else: ?>
        <section class="order-list">
            <?php foreach ($orders as $order): ?>
                <article class="order-row">
                    <div>
                        <span class="order-id">Order #<?php echo (int)$order['id']; ?></span>
                        <h2><?php echo htmlspecialchars($order['restaurant_name']); ?></h2>
                        <p><?php echo htmlspecialchars($order['delivery_address']); ?></p>
                    </div>
                    <div class="order-meta">
                        <span class="status-badge"><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $order['status']))); ?></span>
                        <strong>৳<?php echo number_format((float)$order['total_amount'], 2); ?></strong>
                        <small><?php echo htmlspecialchars(date('M d, Y h:i A', strtotime($order['created_at']))); ?></small>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/../../dirCommon/footer.php'; ?>
