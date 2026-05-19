<?php
session_start();
include '../../dirCommon/dbconnect.php';

$restaurantId = 7; // replace with session value if needed

// Total Orders
$sql = "SELECT COUNT(*) as total_orders FROM orders WHERE restaurant_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $restaurantId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$totalOrders = mysqli_fetch_assoc($result)['total_orders'] ?? 0;

// Total Sales (sum of total_amount where delivered)
$sql = "SELECT SUM(total_amount) as total_sales FROM orders WHERE restaurant_id = ? AND status='delivered'";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $restaurantId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$totalSales = mysqli_fetch_assoc($result)['total_sales'] ?? 0;

// Preparing Orders
$sql = "SELECT COUNT(*) as preparing_orders FROM orders WHERE restaurant_id = ? AND status='preparing'";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $restaurantId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$preparingOrders = mysqli_fetch_assoc($result)['preparing_orders'] ?? 0;

// Completed Orders
$sql = "SELECT COUNT(*) as completed_orders FROM orders WHERE restaurant_id = ? AND status='delivered'";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $restaurantId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$completedOrders = mysqli_fetch_assoc($result)['completed_orders'] ?? 0;





// Recent Sales
$sql = "SELECT o.id, u.name AS customer_name, o.total_amount, o.status
        FROM orders o
        JOIN users u ON o.customer_id = u.id
        WHERE o.restaurant_id = ?
        ORDER BY o.id DESC
        LIMIT 100";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $restaurantId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$recentSales = [];
while ($row = mysqli_fetch_assoc($result)) {
    $recentSales[] = $row;
}


// Top Selling Items
$sql = "SELECT m.name, m.image_path, SUM(oi.quantity) AS total_sold
        FROM order_items oi
        JOIN menu_items m ON oi.menu_item_id = m.id
        JOIN orders o ON oi.order_id = o.id
        WHERE o.restaurant_id = ?
        GROUP BY m.id, m.name, m.image_path
        ORDER BY total_sold DESC
        LIMIT 4";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $restaurantId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$topSellingItems = [];
while ($row = mysqli_fetch_assoc($result)) {
    $topSellingItems[] = $row;
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FOOD OS - Manager Dashboard</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="../assets/css/manager-dashboard.css">
</head>

<body>

<div class="app">

    <!-- ---------- Sidebar ---------- -->

    <aside class="sidebar">

        <div class="brand">
            <div>
                <h2>Bite<span>buddy</span></h2>
            </div>
        </div>

        <nav class="menu">

            <a href="dashboard.php" class="active">
                <span class="menu-icon">▦</span>
                <span>Dashboard</span>
            </a>

            <a href="orders.php">
                <span class="menu-icon">
                    <i class="fa-regular fa-clipboard"></i>
                </span>
                <span>Order</span>
            </a>

            <a href="menu.php">
                <span class="menu-icon">☷</span>
                <span>Menu</span>
            </a>

            <a href="insights.php">
                <span class="menu-icon">
                    <i class="fa-solid fa-chart-line"></i>
                </span>
                <span>Insights</span>
            </a>

            <a href="profile.php">
                <span class="menu-icon">♙</span>
                <span>Profile</span>
            </a>

        </nav>

        <a href="#" class="logout" onclick="confirmLogout(event)">Logout</a>

        <div class="promo">
            <img src="../assets/images/burger.png" alt="Burger">

            <h3>Good Food</h3>
            <p>Good Mood</p>
        </div>

    </aside>

    <!-- ---------- Main Content ---------- -->

    <main class="main-content">

        <!-- ---------- Banner ---------- -->

        <div class="cover-banner">
            <div class="cover-overlay"></div>
        </div>

        <!-- ---------- Welcome Text ---------- -->

        <div class="welcome-text">

            <h1>welcome back, Manager</h1>

            <p>
                Khana's Kitchen is currently
                <span id="restaurantStatus">Online</span>
            </p>

        </div>

        <!-- ---------- Dashboard Cards ---------- -->

        <section class="dashboard-cards">

            <!-- ---------- Total Orders ---------- -->

            <div class="dashboard-card">

                <div class="dash-icon orange-icon">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>

                <div>
                    <p>Total Orders</p>

                <h3 id="totalOrders"><?= $totalOrders ?></h3>

                    <span>All time orders</span>
                </div>

            </div>

            <!-- ---------- Total Sales ---------- -->

            <div class="dashboard-card">

                <div class="dash-icon blue-icon">
                    <i class="fa-solid fa-dollar-sign"></i>
                </div>

                <div>
                    <p>Total Sales</p>

                  <h3 id="totalSales">৳<?= number_format($totalSales,2) ?></h3>

                    <span>Delivered orders</span>
                </div>

            </div>

            <!-- ---------- Preparing Orders ---------- -->

            <div class="dashboard-card">

                <div class="dash-icon yellow-icon">
                    <i class="fa-solid fa-utensils"></i>
                </div>

                <div>
                     <p> Preparing order</p>


                   <h3 id="preparingOrders"><?= $preparingOrders ?></h3>

                    <span>In kitchen</span>
                </div>

            </div>

            <!-- ---------- Completed Orders ---------- -->

            <div class="dashboard-card">

                <div class="dash-icon green-icon">
                    <i class="fa-solid fa-circle-check"></i>
                </div>

                <div>
                    <p>Completed Orders</p>

                    <h3 id="completedOrders"><?= $completedOrders ?></h3>

                    <span>Successfully done</span>
                </div>

            </div>

        </section>
















        <!-- ---------- Dashboard Body ---------- -->

        <section class="dashboard-body">

           
<!-- ---------- Dashboard Bottom ---------- -->

<section class="dashboard-bottom-grid">

    <!-- Recent Sales -->

    <div class="dashboard-panel recent-sales-box">

        <div class="panel-heading">
           <h2>Recent Sales</h2>
           <a href="#" id="viewAllSales">View All</a>
        </div> 

        <?php foreach($recentSales as $sale): ?>
            <div class="recent-sale-item sale-hidden">
                <img src="../assets/images/burger.png">
                <div class="sale-info">
                    <h4>#ORD-<?php echo $sale['id']; ?></h4>
                    <p><?php echo $sale['customer_name']; ?></p>
                </div>

             <strong>৳<?php echo $sale['total_amount']; ?></strong>

                    <span class="sale-status <?php echo $sale['status']; ?>">
                    <?php echo ucfirst($sale['status']); ?>
                  </span>

            </div>

           <?php endforeach; ?>

        


    </div>

    <!-- Top Selling -->

    <div class="dashboard-panel top-selling-box">

        <div class="panel-heading">
            <h2>Top Selling Items</h2>
        </div>

        <div class="top-selling-grid">

            <div class="top-food-card">

                <img src="../assets/images/burger.png">

                <h4>BBQ Burger</h4>

                <p>120 Orders</p>

            </div>

            <div class="top-food-card">

                <img src="../assets/images/pizza.png">

                <h4>Cheese Pizza</h4>

                <p>98 Orders</p>

            </div>

            <div class="top-food-card">

                <img src="../assets/images/fries.png">

                <h4>French Fries</h4>

                <p>75 Orders</p>

            </div>

            <div class="top-food-card">

                <img src="../assets/images/coca.png">

                <h4>Cold Coffee</h4>

                <p>65 Orders</p>

            </div>

        </div>

    </div>

</section>




  <!-- ---------- Restaurant Status ---------- -->

                <div class="restaurant-status-box">

                    <div>

                        <h3>Restaurant Status</h3>

                        <p>
                            Your restaurant is currently
                            <span id="restaurantStatusText">
                                Online
                            </span>
                        </p>

                    </div>

                    <!-- ---------- Switch ---------- -->

                    <label class="switch">

                        <input
                            type="checkbox"
                            id="restaurantToggle"
                            checked
                        >

                        <span class="slider"></span>

                    </label>

                </div>



<script>
function updateOrderStatus(orderId, newStatus){
    if(!confirm(`Are you sure you want to mark order #${orderId} as ${newStatus}?`)) return;

    fetch('../controller/updateOrder.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ order_id: orderId, status: newStatus })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            // Remove order from Incoming Orders list
            const orderElem = document.getElementById('order-' + orderId);
            if(orderElem) orderElem.remove();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => alert('AJAX Error: ' + err));
}
</script>


<script>
function confirmLogout(event) {
    event.preventDefault();

    let logoutConfirm = confirm("Are you sure you want to logout?");

    if (logoutConfirm) {
        window.location.href = "../../dirCommon/login.html";
    }
}
</script>


<script>
document.getElementById('viewAllSales').addEventListener('click', function(event) {
    event.preventDefault();

    const salesBox = document.querySelector('.recent-sales-box');
    salesBox.classList.toggle('expanded');

    this.innerText = salesBox.classList.contains('expanded') ? 'Show Less' : 'View All';
});
</script>



<script>
document.getElementById('restaurantToggle').addEventListener('change', function() {
    const statusText = document.getElementById('restaurantStatusText');
    const topStatus = document.getElementById('restaurantStatus');

    if (this.checked) {
        statusText.innerText = 'Online';
        if (topStatus) topStatus.innerText = 'Online';
    } else {
        statusText.innerText = 'Closed';
        if (topStatus) topStatus.innerText = 'Closed';
    }
});
</script>

</body>
</html>