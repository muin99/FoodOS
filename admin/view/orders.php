<?php

$pageTitle  = 'All Orders - Admin';
$activePage = 'orders';
$basePath   = '../../';

include '../controller/orderController.php';

if (!isset($orders)) {
    $orders = null;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?></title>

<link rel="stylesheet" href="../assets/css/adminDashboard.css">
<link rel="stylesheet" href="../assets/css/orders.css">

</head>

<body>

<div class="admin-wrap">

    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <div class="main">

        <div class="topbar">
            <h2>📦 All Orders</h2>
        </div>

        <form class="filter-box" method="GET">

            <select name="status">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="preparing">Preparing</option>
                <option value="ready">Ready</option>
                <option value="picked_up">Picked Up</option>
                <option value="delivered">Delivered</option>
            </select>

            <input type="number" name="restaurant_id" placeholder="Restaurant ID">
            <input type="number" name="customer_id" placeholder="Customer ID">

            <input type="date" name="from">
            <input type="date" name="to">

            <button type="submit">Filter</button>

        </form>
        <div class="table-section">

            <table class="orders-table">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Restaurant</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>

                <?php if ($orders && $orders->num_rows > 0): ?>

                    <?php while($row = $orders->fetch_assoc()): ?>

                        <tr>
                            <td>#<?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['customer_name']) ?></td>
                            <td><?= htmlspecialchars($row['restaurant_name']) ?></td>

                            <td>
                                <span class="status <?= $row['status'] ?>">
                                    <?= $row['status'] ?>
                                </span>
                            </td>

                            <td><?= $row['total_amount'] ?> ৳</td>
                            <td><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>
                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="6">No orders found</td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>