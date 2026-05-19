<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (($_SESSION['user_role'] ?? '') !== 'agent' || !isset($_SESSION['agent_id'], $_SESSION['user_id'])) {
    header('Location: ../../dirCommon/login.html');
    exit;
}

include __DIR__ . '/../../dirCommon/dbconnect.php';
include __DIR__ . '/../model/agentModel.php';

$agentId = (int)$_SESSION['agent_id'];
$userId = (int)$_SESSION['user_id'];
$stats = getAgentPerformanceStats($conn, $agentId, $userId);
$earnings = getAgentEarningsSummary($conn, $agentId);
$activeAssignment = getActiveAssignment($conn, $agentId);
$availableOrders = getAvailableAssignments($conn);
$history = array_slice(getAgentDeliveryHistory($conn, $agentId), 0, 5);
$agentName = $_SESSION['user_name'] ?? ($stats['name'] ?? 'Delivery Agent');
$isOnline = (int)($stats['is_online'] ?? 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BiteBuddy - Delivery Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
    <header class="agent-header">
        <div class="header-inner">
            <a class="brand" href="../../index.php">BiteBuddy</a>
            <div class="agent-actions">
                <label class="status-pill">
                    <input id="onlineToggle" type="checkbox" <?php echo $isOnline ? 'checked' : ''; ?>>
                    <span id="onlineText"><?php echo $isOnline ? 'Online' : 'Offline'; ?></span>
                </label>
                <a class="logout-link" href="../controller/logout.php">Logout</a>
            </div>
        </div>
    </header>

    <main class="agent-wrap">
        <section class="agent-hero">
            <div>
                <h1>Hello, <?php echo htmlspecialchars($agentName); ?></h1>
                <p>Accept ready orders, update delivery progress, and track your earnings.</p>
            </div>
        </section>

        <section class="grid stats-grid">
            <div class="card"><span>Total Earnings</span><strong>৳<?php echo number_format((float)$earnings['total_earnings'], 2); ?></strong></div>
            <div class="card"><span>Deliveries</span><strong><?php echo (int)$earnings['total_deliveries']; ?></strong></div>
            <div class="card"><span>Vehicle</span><strong><?php echo htmlspecialchars($stats['vehicle_type'] ?? '-'); ?></strong></div>
            <div class="card"><span>Status</span><strong id="statusCardText"><?php echo $isOnline ? 'Online' : 'Offline'; ?></strong></div>
        </section>

        <section class="grid main-grid">
            <div>
                <div class="section-title">
                    <h2>Active Delivery</h2>
                    <button class="btn secondary" type="button" onclick="location.reload()">Refresh</button>
                </div>

                <?php if ($activeAssignment): ?>
                    <article class="assignment-card">
                        <div class="assignment-top">
                            <div>
                                <h3>Order #<?php echo (int)$activeAssignment['order_id']; ?></h3>
                                <p><?php echo htmlspecialchars($activeAssignment['restaurant_name']); ?> to <?php echo htmlspecialchars($activeAssignment['customer_name']); ?></p>
                            </div>
                            <div class="amount">৳<?php echo number_format((float)$activeAssignment['delivery_fee'], 2); ?></div>
                        </div>
                        <div class="route">
                            <div><span>Pickup</span><p><?php echo htmlspecialchars($activeAssignment['restaurant_address']); ?></p></div>
                            <div><span>Drop-off</span><p><?php echo htmlspecialchars($activeAssignment['delivery_address']); ?></p></div>
                        </div>
                        <div class="button-row">
                            <?php if ($activeAssignment['assignment_status'] === 'assigned'): ?>
                                <button class="btn success status-btn" data-order-id="<?php echo (int)$activeAssignment['order_id']; ?>" data-status="picked_up">Mark Picked Up</button>
                            <?php endif; ?>
                            <button class="btn status-btn" data-order-id="<?php echo (int)$activeAssignment['order_id']; ?>" data-status="delivered">Mark Delivered</button>
                            <button class="btn secondary status-btn" data-order-id="<?php echo (int)$activeAssignment['order_id']; ?>" data-status="cancelled">Cancel</button>
                        </div>
                    </article>
                <?php else: ?>
                    <div class="empty">No active delivery right now.</div>
                <?php endif; ?>

                <div class="section-title">
                    <h2>Available Orders</h2>
                    <span><?php echo count($availableOrders); ?> ready</span>
                </div>

                <div class="assignment-list">
                    <?php if (count($availableOrders) === 0): ?>
                        <div class="empty">No available orders at the moment.</div>
                    <?php endif; ?>

                    <?php foreach ($availableOrders as $order): ?>
                        <article class="assignment-card">
                            <div class="assignment-top">
                                <div>
                                    <h3><?php echo htmlspecialchars($order['restaurant_name']); ?></h3>
                                    <p>Order #<?php echo (int)$order['order_id']; ?> for <?php echo htmlspecialchars($order['customer_name']); ?></p>
                                </div>
                                <div class="amount">৳<?php echo number_format((float)$order['delivery_fee'], 2); ?></div>
                            </div>
                            <div class="route">
                                <div><span>Pickup</span><p><?php echo htmlspecialchars($order['restaurant_address']); ?></p></div>
                                <div><span>Drop-off</span><p><?php echo htmlspecialchars($order['delivery_address']); ?></p></div>
                            </div>
                            <div class="button-row">
                                <button class="btn accept-btn" data-order-id="<?php echo (int)$order['order_id']; ?>">Accept Order</button>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <div class="section-title">
                    <h2>Recent History</h2>
                </div>
                <div class="history-list">
                    <?php if (count($history) === 0): ?>
                        <div class="empty">No delivery history yet.</div>
                    <?php endif; ?>
                    <?php foreach ($history as $item): ?>
                        <article class="assignment-card">
                            <div class="assignment-top">
                                <div>
                                    <h3>Order #<?php echo (int)$item['order_id']; ?></h3>
                                    <p><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $item['status']))); ?> - <?php echo htmlspecialchars($item['delivery_address']); ?></p>
                                </div>
                                <div class="amount">৳<?php echo number_format((float)$item['delivery_fee'], 2); ?></div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>

            <aside class="profile-card">
                <h2>Agent Profile</h2>
                <form id="profileForm">
                    <label>
                        <span>Name</span>
                        <input name="name" value="<?php echo htmlspecialchars($stats['name'] ?? $agentName); ?>" required>
                    </label>
                    <label>
                        <span>Phone</span>
                        <input name="phone" value="<?php echo htmlspecialchars($stats['phone'] ?? ''); ?>" required>
                    </label>
                    <label>
                        <span>Vehicle</span>
                        <select name="vehicle_type" required>
                            <?php foreach (['bike', 'cycle', 'car', 'scooter'] as $vehicle): ?>
                                <option value="<?php echo $vehicle; ?>" <?php echo (($stats['vehicle_type'] ?? '') === $vehicle) ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($vehicle); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <button class="btn" type="submit">Save Profile</button>
                    <p id="profileMessage" class="message"></p>
                </form>
            </aside>
        </section>
    </main>

    <script src="../js/dashboard.js"></script>
</body>
</html>
