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

    <link rel="stylesheet" href="../../assets/css/manager-dashboard.css">
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

        <a href="#" class="logout">Logout</a>

        <div class="promo">
            <img src="../../assets/images/burger.png" alt="Burger">

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

                    <h3 id="totalOrders">0</h3>

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

                    <h3 id="totalSales">৳0</h3>

                    <span>Delivered orders</span>
                </div>

            </div>

            <!-- ---------- Preparing Orders ---------- -->

            <div class="dashboard-card">

                <div class="dash-icon yellow-icon">
                    <i class="fa-solid fa-utensils"></i>
                </div>

                <div>
                    <p>Preparing Orders</p>

                    <h3 id="preparingOrders">0</h3>

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

                    <h3 id="completedOrders">0</h3>

                    <span>Successfully done</span>
                </div>

            </div>

        </section>

        <!-- ---------- Dashboard Body ---------- -->

        <section class="dashboard-body">

            <!-- ---------- Incoming Orders ---------- -->

            <div class="dashboard-panel incoming-panel">

                <div class="panel-heading">

                    <h2>Incoming Orders</h2>

                    <span id="incomingOrdersCount">0 New</span>

                </div>

                <!-- ---------- Incoming Orders Container ---------- -->

                <div id="incomingOrdersContainer">

                    <!-- JS Will Add Orders Here -->

                    <div class="mini-order active-mini-order">

                        <div class="mini-order-top">

                            <div>
                                <h4>Order #0000</h4>
                                <small>Waiting for orders...</small>
                            </div>

                            <h3>৳0</h3>

                        </div>

                        <p>No incoming orders right now.</p>

                        <div class="mini-order-actions">

                            <button type="button" class="accept-btn">
                                Accept
                            </button>

                            <button type="button" class="reject-btn">
                                Reject
                            </button>

                        </div>

                    </div>

                </div>

            </div>

            <!-- ---------- Active Kitchen ---------- -->

            <div class="dashboard-panel kitchen-panel">

                <div class="panel-heading">

                    <h2>Active Kitchen</h2>

                    <div class="kitchen-tags">

                        <span class="tag-red">
                            Preparing
                            <span id="preparingKitchenCount">0</span>
                        </span>

                        <span class="tag-green">
                            Ready
                            <span id="readyKitchenCount">0</span>
                        </span>

                    </div>

                </div>

                <!-- ---------- Kitchen Table ---------- -->

                <table class="kitchen-table">

                    <thead>

                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th>Remaining</th>
                        </tr>

                    </thead>

                    <!-- ---------- Kitchen Table Body ---------- -->

                    <tbody id="activeKitchenTable">

                        <!-- JS Will Add Kitchen Rows Here -->

                        <tr>

                            <td>#0000</td>
                            <td>No Orders</td>
                            <td>৳0</td>

                            <td>
                                <span class="status preparing">
                                    Waiting
                                </span>
                            </td>

                            <td class="time-red">0 min</td>

                        </tr>

                    </tbody>

                </table>

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

            </div>

        </section>

    </main>

</div>

</body>
</html>