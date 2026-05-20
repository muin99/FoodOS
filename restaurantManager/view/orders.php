<?php
// orders.php
include __DIR__ . '/../controller/managerSession.php';
managerRequirePage();
include __DIR__ . '/../../dirCommon/dbconnect.php';

$managerId = $_SESSION['user_id'] ?? 0;
$restaurantId = 0;

$stmt = mysqli_prepare($conn, "SELECT id FROM restaurants WHERE manager_id = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $managerId);
mysqli_stmt_execute($stmt);
$restaurantResult = mysqli_stmt_get_result($stmt);
$restaurant = mysqli_fetch_assoc($restaurantResult);

if ($restaurant) {
    $restaurantId = (int)$restaurant['id'];
}

// Fetch total orders, preparing, completed
$totalOrdersQuery = $conn->query("SELECT COUNT(*) as total FROM orders WHERE restaurant_id = $restaurantId");
$totalOrders = $totalOrdersQuery->fetch_assoc()['total'] ?? 0;

$preparingOrdersQuery = $conn->query("SELECT COUNT(*) as total FROM orders WHERE restaurant_id = $restaurantId AND status='preparing'");
$preparingOrders = $preparingOrdersQuery->fetch_assoc()['total'] ?? 0;

$completedOrdersQuery = $conn->query("SELECT COUNT(*) as total FROM orders WHERE restaurant_id = $restaurantId AND status='delivered'");
$completedOrders = $completedOrdersQuery->fetch_assoc()['total'] ?? 0;

// Fetch active restaurant orders
$pendingOrdersResult = $conn->query("SELECT * FROM orders WHERE restaurant_id = $restaurantId AND status IN ('pending', 'accepted', 'preparing') ORDER BY id DESC");
$pendingOrders = [];
if($pendingOrdersResult){
    while($row = $pendingOrdersResult->fetch_assoc()){
        $pendingOrders[] = $row;
    }
}

// Fetch full order history
$historyResult = $conn->query("SELECT * FROM orders WHERE restaurant_id = $restaurantId ORDER BY id DESC");
$orderHistory = [];
if($historyResult){
    while($row = $historyResult->fetch_assoc()){
        $orderHistory[] = $row;
    }
}

$orderQuery = $conn->query("SELECT * FROM orders WHERE restaurant_id = $restaurantId ORDER BY id DESC");
$allOrders = $orderQuery->fetch_all(MYSQLI_ASSOC);
?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Food OS - Orders</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

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

            <a href="dashboard.php">
                <span class="menu-icon">▦</span>
                <span>Dashboard</span>
            </a>

            <a href="orders.php" class="active">
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

        <!-- ---------- Topbar ---------- -->

        <header class="page-topbar">
            <div class="topbar-bg"></div>

            <div class="notification">
                <i class="fa-regular fa-bell"></i>
                <span id="orderNotificationCount">0</span>
            </div>
        </header>

        <!-- ---------- Order Summary Cards ---------- -->

        <section class="orders-summary">

            <div class="summary-card" data-card="total-orders">

                <div class="summary-icon orange-icon">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>

                <div>
                    <p>Total Order</p>
                   <h3><?= $totalOrders ?></h3>
                    <span id="totalOrderNote">Waiting for data</span>
                </div>

            </div>

            <div class="summary-card" data-card="preparing-orders">

                <div class="summary-icon blue-icon">
                    <i class="fa-solid fa-utensils"></i>
                </div>

                <div>
                    <p>Preparing Order</p>
                   <h3><?= $preparingOrders ?></h3>
                    <span class="blue-text" id="preparingOrderNote">In Progress</span>
                </div>

            </div>

            <div class="summary-card" data-card="completed-orders">

                <div class="summary-icon green-icon">
                    <i class="fa-solid fa-circle-check"></i>
                </div>

                <div>
                    <p>Completed Orders</p>
                    <h3><?= $completedOrders ?></h3>
                    <span id="completedOrderNote">Waiting for data</span>
                </div>

            </div>

            <a href="#fullHistory" class="summary-card history-card" data-action="scroll-history">

                <div class="summary-icon purple-icon">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>

                <div>
                    <p>Full History</p>
                    <h3>View</h3>
                    <span>See all past orders</span>
                </div>

            </a>

        </section>

        <!-- ---------- Pending Orders And Action ---------- -->

        <section class="order-action-area">

            <!-- ---------- Pending Orders ---------- -->

            <div class="pending-orders-box">
    <div class="panel-heading">
        <h2>Active Orders</h2>
        <span><?= count($pendingOrders) ?> New</span>
    </div>

    <?php foreach($pendingOrders as $order): ?>
        <div class="pending-order-card" id="order-<?= $order['id'] ?>">
            <h3>Order #<?= $order['id'] ?></h3>
            <p>Status: <?= htmlspecialchars(ucfirst($order['status'])) ?></p>
            <p>Total: ৳<?= number_format($order['total_amount'], 2) ?></p>
            <p>Address: <?= htmlspecialchars($order['delivery_address']) ?></p>

            <?php if ($order['status'] === 'pending'): ?>
                <button type="button" class="order-status-btn" data-order-id="<?= (int)$order['id'] ?>" data-status="accepted">Accept</button>
                <button type="button" class="order-status-btn" data-order-id="<?= (int)$order['id'] ?>" data-status="cancelled">Reject</button>
            <?php else: ?>
                <button type="button" class="order-status-btn" data-order-id="<?= (int)$order['id'] ?>" data-status="ready">Ready to Pickup</button>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    <?php if(count($pendingOrders) === 0): ?>
        <p>No active orders right now.</p>
    <?php endif; ?>
</div>


        


            <!-- ---------- Selected Order Action ---------- -->

            <div class="order-action-box">

                <h2>Order Action</h2>

                <h3 id="selectedOrderId">No Order</h3>

                <p id="selectedOrderMessage">
                    Select a pending order to accept or reject.
                </p>

                <div class="order-action-buttons">

                    <button
                        type="button"
                        class="accept-order-btn"
                        id="acceptOrderBtn"
                        data-action="accept-order"
                        data-order-id=""
                    >
                        Accept
                    </button>

                    <button
                        type="button"
                        class="reject-order-btn"
                        id="rejectOrderBtn"
                        data-action="reject-order"
                        data-order-id=""
                    >
                        Reject
                    </button>

                </div>

            </div>

        </section>

        <!-- ---------- Full Order History ---------- -->

        <section class="full-history-section" id="fullHistory">

            <div class="history-header">

                <div>
                    <h2>Full Order History</h2>
                    <p>All restaurant orders from database</p>
                </div>

                <div class="history-tools">

                    <input
                        type="text"
                        id="orderSearchInput"
                        placeholder="Search order..."
                        data-action="search-order"
                    >

                    <select id="orderSortSelect" data-action="sort-order">
                        <option value="latest">Sort by Latest</option>
                        <option value="amount">Sort by Total Amount</option>
                        <option value="pending">Pending</option>
                        <option value="preparing">Preparing</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>

                </div>

            </div>

            <div class="history-table-box">

                <table class="history-table">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer ID</th>
                            <th>Restaurant ID</th>
                            <th>Agent ID</th>
                            <th>Payment</th>
                            <th>Subtotal</th>
                            <th>Fee</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Est. Time</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody id="orderHistoryTable">
<?php if(count($allOrders) > 0): ?>
    <?php foreach($allOrders as $order): ?>
        <tr>
            <td><?= $order['id'] ?></td>
            <td><?= $order['customer_id'] ?></td>
            <td><?= $order['restaurant_id'] ?></td>
            <td><?= $order['agent_id'] ?></td>
            <td><?= $order['payment_method'] ?></td>
            <td><?= number_format($order['subtotal'], 2) ?></td>
            <td><?= number_format($order['delivery_fee'], 2) ?></td>
            <td><?= number_format($order['total_amount'], 2) ?></td>
            <td><?= ucfirst($order['status']) ?></td>
            <td><?= $order['estimated_delivery_minutes'] ?> min</td>
            <td><?= $order['created_at'] ?></td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="11">No order history loaded yet.</td></tr>
<?php endif; ?>
</tbody>


                    
                </table>

            </div>

        </section>

    </main>

</div>



<script>
document.getElementById("orderSearchInput").addEventListener("keyup", function () {
    let searchValue = this.value.toLowerCase();
    let rows = document.querySelectorAll("#orderHistoryTable tr");

    rows.forEach(function (row) {
        let rowText = row.innerText.toLowerCase();

        if (rowText.includes(searchValue)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});
</script>

<script>
document.getElementById("orderSortSelect").addEventListener("change", function () {
    let sortValue = this.value;
    let tbody = document.getElementById("orderHistoryTable");
    let rows = Array.from(tbody.querySelectorAll("tr"));

    rows.sort(function (a, b) {
        let aCells = a.querySelectorAll("td");
        let bCells = b.querySelectorAll("td");

        if (sortValue === "latest") {
            return Number(bCells[0].innerText) - Number(aCells[0].innerText);
        }

        if (sortValue === "amount") {
            return parseFloat(bCells[7].innerText.replace("৳", "")) - parseFloat(aCells[7].innerText.replace("৳", ""));
        }

        let statusA = aCells[8].innerText.toLowerCase();
        let statusB = bCells[8].innerText.toLowerCase();

        if (statusA === sortValue && statusB !== sortValue) return -1;
        if (statusA !== sortValue && statusB === sortValue) return 1;
        return 0;
    });

    rows.forEach(function (row) {
        tbody.appendChild(row);
    });
});
</script>



<script>
document.querySelectorAll(".ready-order-btn").forEach(function(btn){
    btn.addEventListener("click", function(){
        let orderId = this.dataset.orderId;

        let formData = new FormData();
        formData.append("id", orderId);

        fetch("../controller/orderControl.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);

            if(data.success){
                document.getElementById("order-" + orderId).remove();
                location.reload();
            }
        });
    });
});
</script>

<script>
document.querySelectorAll(".order-status-btn").forEach(function(btn){
    btn.addEventListener("click", function(){
        let orderId = this.dataset.orderId;
        let status = this.dataset.status;
        let formData = new FormData();
        formData.append("id", orderId);
        formData.append("status", status);

        fetch("../controller/orderControl.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            if(data.success){
                location.reload();
            }
        });
    });
});

document.querySelectorAll(".pending-order-card").forEach(function(card) {
    card.addEventListener("click", function(event) {
        if (event.target.tagName.toLowerCase() === "button") return;
        const orderId = card.id.replace("order-", "");
        document.getElementById("selectedOrderId").innerText = "Order #" + orderId;
        document.getElementById("selectedOrderMessage").innerText = "Choose an action for this order.";
        document.getElementById("acceptOrderBtn").dataset.orderId = orderId;
        document.getElementById("rejectOrderBtn").dataset.orderId = orderId;
    });
});

document.getElementById("acceptOrderBtn").addEventListener("click", function() {
    if (!this.dataset.orderId) return alert("Select an order first.");
    document.querySelector('[data-order-id="' + this.dataset.orderId + '"][data-status="accepted"]')?.click();
});

document.getElementById("rejectOrderBtn").addEventListener("click", function() {
    if (!this.dataset.orderId) return alert("Select an order first.");
    document.querySelector('[data-order-id="' + this.dataset.orderId + '"][data-status="cancelled"]')?.click();
});
</script>

<script>

</script>

<script>
function confirmLogout(event) {
    event.preventDefault();

    let logoutConfirm = confirm("Are you sure you want to logout?");

    if (logoutConfirm) {
        window.location.href = "../controller/logout.php";
    }
}
</script>



</body>
</html>
