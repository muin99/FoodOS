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

        <a href="#" class="logout">Logout</a>

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
                    <h3 id="totalOrderCount">0</h3>
                    <span id="totalOrderNote">Waiting for data</span>
                </div>

            </div>

            <div class="summary-card" data-card="preparing-orders">

                <div class="summary-icon blue-icon">
                    <i class="fa-solid fa-utensils"></i>
                </div>

                <div>
                    <p>Preparing Order</p>
                    <h3 id="preparingOrderCount">0</h3>
                    <span class="blue-text" id="preparingOrderNote">In Progress</span>
                </div>

            </div>

            <div class="summary-card" data-card="completed-orders">

                <div class="summary-icon green-icon">
                    <i class="fa-solid fa-circle-check"></i>
                </div>

                <div>
                    <p>Completed Orders</p>
                    <h3 id="completedOrderCount">0</h3>
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
                    <h2>Pending Orders</h2>
                    <span id="pendingOrderCount">0 New</span>
                </div>

                <div id="pendingOrdersContainer">

                    <!-- JS will load pending order cards here -->

                    <div class="pending-order-card" data-order-id="">
                        <h3>No Pending Orders</h3>
                        <p>All orders are currently handled.</p>
                    </div>

                </div>

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

                        <!-- JS will load order history rows here -->

                        <tr>
                            <td colspan="11">No order history loaded yet.</td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </section>

    </main>

</div>

</body>
</html>