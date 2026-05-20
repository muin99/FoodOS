<?php
$pageTitle  = "Platform Settings";
$activePage = "settings";
$basePath   = "../../";

include '../controller/settingsController.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= $pageTitle ?></title>

<link rel="stylesheet" href="../assets/css/adminDashboard.css">
<link rel="stylesheet" href="../assets/css/settings.css">
</head>

<body>

<div class="admin-wrap">

<?php include '../partials/sidebar.php'; ?>

<div class="main">

    <div class="topbar">
        <h2>Platform Settings & Analytics</h2>
    </div>

    <div class="cards">

        <div class="card">
            <p>Total Revenue</p>
            <h3>$<?= number_format($totalRevenue, 2) ?></h3>
        </div>

        <div class="card">
            <p>Avg Delivery Time</p>
            <h3><?= round($avgDeliveryTime) ?> min</h3>
        </div>

        <div class="card">
            <p>On-time Rate</p>
            <h3><?= round($onTimeRate) ?>%</h3>
        </div>

        <div class="card">
            <p>Failed Deliveries</p>
            <h3><?= $failedDeliveries ?></h3>
        </div>

    </div>

    <div class="content">

        <div class="table-section">
            <div class="table-header">
                <h3>Monthly Summary</h3>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Orders</th>
                        <th>Revenue</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($monthlySummary)): ?>
                        <tr>
                            <td><?= $row['month'] ?></td>
                            <td><?= $row['total_orders'] ?></td>
                            <td>$<?= $row['revenue'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="right-panel">

            <div class="box settings">

                <h3>Global Configuration</h3>

                <form method="POST">

                    <label>Commission Rate (%)</label>
                    <input type="number" name="commission_rate"
                           value="<?= $commissionRate ?>">

                    <label>Base Delivery Fee</label>
                    <input type="number" step="0.01" name="delivery_fee"
                           value="<?= $deliveryFee ?>">

                    <button class="save-btn">Save</button>
                </form>
            </div>

            <div class="box">
                <h3>Top Restaurants</h3>

                <?php while ($r = mysqli_fetch_assoc($topRestaurants)): ?>
                    <p><?= $r['name'] ?> - <?= $r['total_orders'] ?> orders</p>
                <?php endwhile; ?>
            </div>

            <!-- TOP AGENTS -->
            <div class="box">
                <h3>Top Delivery Agents</h3>

                <?php while ($a = mysqli_fetch_assoc($topAgents)): ?>
                    <p><?= $a['name'] ?> - <?= $a['total_orders'] ?> deliveries</p>
                <?php endwhile; ?>
            </div>

        </div>

    </div>

</div>

</div>

</body>
</html>