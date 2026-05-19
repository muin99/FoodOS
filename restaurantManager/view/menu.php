<?php
session_start();
include '../../dirCommon/dbconnect.php'; // your DB connection

$managerId = $_SESSION['user_id'] ?? 0;
$restaurantId = 0;

$stmt = mysqli_prepare($conn, "SELECT id FROM restaurants WHERE manager_id = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $managerId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$restaurant = mysqli_fetch_assoc($result);

if ($restaurant) {
    $restaurantId = (int)$restaurant['id'];
}

// ---------- Fetch category counts ----------
$categoryCounts = [];
$sql = "SELECT category_id, COUNT(*) as count 
        FROM menu_items 
        WHERE restaurant_id = ? 
        GROUP BY category_id";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $restaurantId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while($row = mysqli_fetch_assoc($result)){
    $categoryCounts[$row['category_id']] = $row['count'];
}

// ---------- Fetch all categories ----------
$sql = "SELECT * FROM menu_categories WHERE restaurant_id = ? ORDER BY display_order";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $restaurantId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$categories = [];
while($row = mysqli_fetch_assoc($result)){
    $categories[$row['id']] = $row;
}

// ---------- Selected category ----------
$selectedCategoryId = $_GET['category_id'] ?? array_key_first($categories) ?? 0;

// ---------- Fetch menu items for selected category ----------
$sql = "SELECT * FROM menu_items WHERE restaurant_id = ? AND category_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $restaurantId, $selectedCategoryId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$menuItems = [];
while($row = mysqli_fetch_assoc($result)){
    $menuItems[] = $row;
}
?>








<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Food OS - Menu</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="../assets/css/manager-dashboard.css">
</head>

<body>

<div class="app">

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

            <a href="orders.php">
                <span class="menu-icon">
                    <i class="fa-regular fa-clipboard"></i>
                </span>
                <span>Order</span>
            </a>

            <a href="menu.php" class="active">
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

    <main class="main-content">

        <header class="page-topbar">
            <div class="topbar-bg"></div>
        </header>

        <section class="menu-page">

            <div class="menu-page-header">

                <div>
                    <h1>Menu Management</h1>
                </div>

                <div class="menu-header-buttons">

                    

                    <button type="button" class="solid-btn" id="addItemBtn" data-action="add-item">
                        <i class="fa-solid fa-plus"></i>
                        Add Item
                    </button>

                </div>

            </div>

            <div class="menu-tabs">
                <button type="button" class="active" id="categoryTab" data-tab="categories">
                    Categories
                </button>
            </div>

            <section class="menu-management-area">

                <div class="category-box" id="categoryList">

                    <!-- JS can load categories here later -->

                    <a href="menu.php?category_id=1" class="category-row <?= $selectedCategoryId == 1 ? 'active-category' : '' ?>" data-category-name="burgers">
                           <span class="category-icon">🍔</span>
                            <strong>Burgers</strong>
                           <small id="burgerItemCount"><?= $categoryCounts[1] ?? 0 ?> Items</small>
                        <i class="fa-solid fa-angle-right"></i>
                    </a>

                    <a href="menu.php?category_id=2" class="category-row <?= $selectedCategoryId == 2 ? 'active-category' : '' ?>" data-category-name="pizzas">
                            <span class="category-icon">🍕</span>
                            <strong>Pizzas</strong>
                            <small id="pizzaItemCount"><?= $categoryCounts[2] ?? 0 ?> Items</small>
                            <i class="fa-solid fa-angle-right"></i>
                    </a>

                    <a href="menu.php?category_id=3" class="category-row <?= $selectedCategoryId == 3 ? 'active-category' : '' ?>" data-category-name="drinks">
                            <span class="category-icon">🥤</span>
                            <strong>Drinks</strong>
                            <small id="drinkItemCount"><?= $categoryCounts[3] ?? 0 ?> Items</small>
                            <i class="fa-solid fa-angle-right"></i>
                    </a>

                    <a href="menu.php?category_id=4" class="category-row <?= $selectedCategoryId == 4 ? 'active-category' : '' ?>" data-category-name="sides">
                            <span class="category-icon">🍟</span>
                            <strong>Sides</strong>
                            <small id="sideItemCount"><?= $categoryCounts[4] ?? 0 ?> Items</small>
                            <i class="fa-solid fa-angle-right"></i>
                    </a>

                    <a href="menu.php?category_id=5" class="category-row <?= $selectedCategoryId == 5 ? 'active-category' : '' ?>" data-category-name="desserts">
                          <span class="category-icon">🍰</span>
                         <strong>Desserts</strong>
                         <small id="dessertItemCount"><?= $categoryCounts[5] ?? 0 ?> Items</small>
                          <i class="fa-solid fa-angle-right"></i>
                    </a>

                </div>

                <div class="items-box">

                    <div class="items-box-header">
                        <h2 id="selectedCategoryTitle">Burgers</h2>
                        <span id="menuScrollText">Scroll for more items</span>
                    </div>

                    <div class="items-table-wrapper">

                        <table class="menu-items-table">

                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th id="priceHeader" style="cursor:pointer;" >Price &#9650;</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody id="menuItemsTable">

                                <!-- JS can load menu items here later -->

                                <tr data-item-id="">
                                    <td>
                                        <div class="item-info">
                                            <img src="../assets/images/burger.png" alt="Menu Item">
                                            <div>
                                                <h4 id="firstMenuItemName">No item loaded</h4>
                                                <p id="firstMenuItemDescription">Menu items will appear here after JS/AJAX.</p>
                                            </div>
                                        </div>
                                    </td>

                                    <td id="firstMenuItemPrice">৳0</td>

                                    <td>
                                        <span class="available-badge" id="firstMenuItemStatus">
                                            Waiting
                                        </span>
                                    </td>

                                    <td>
                                        <button type="button" class="edit-item-btn" data-action="edit-item" data-item-id="">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>

                                        <button type="button" class="delete-item-btn" data-action="delete-item" data-item-id="">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </section>

        </section>

    </main>

</div>


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
