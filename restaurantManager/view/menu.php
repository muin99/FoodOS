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

        <a href="#" class="logout">Logout</a>

        <div class="promo">
            <img src="../../assets/images/burger.png" alt="Burger">
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

                    <button type="button" class="outline-btn" id="addCategoryBtn" data-action="add-category">
                        <i class="fa-solid fa-list"></i>
                        Add Category
                    </button>

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

                    <button type="button" class="category-row active-category" data-category-id="" data-category-name="burgers">
                        <span class="category-icon">🍔</span>
                        <strong>Burgers</strong>
                        <small id="burgerItemCount">0 Items</small>
                        <i class="fa-solid fa-angle-right"></i>
                    </button>

                    <button type="button" class="category-row" data-category-id="" data-category-name="pizzas">
                        <span class="category-icon">🍕</span>
                        <strong>Pizzas</strong>
                        <small id="pizzaItemCount">0 Items</small>
                        <i class="fa-solid fa-angle-right"></i>
                    </button>

                    <button type="button" class="category-row" data-category-id="" data-category-name="drinks">
                        <span class="category-icon">🥤</span>
                        <strong>Drinks</strong>
                        <small id="drinkItemCount">0 Items</small>
                        <i class="fa-solid fa-angle-right"></i>
                    </button>

                    <button type="button" class="category-row" data-category-id="" data-category-name="sides">
                        <span class="category-icon">🍟</span>
                        <strong>Sides</strong>
                        <small id="sideItemCount">0 Items</small>
                        <i class="fa-solid fa-angle-right"></i>
                    </button>

                    <button type="button" class="category-row" data-category-id="" data-category-name="desserts">
                        <span class="category-icon">🍰</span>
                        <strong>Desserts</strong>
                        <small id="dessertItemCount">0 Items</small>
                        <i class="fa-solid fa-angle-right"></i>
                    </button>

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
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody id="menuItemsTable">

                                <!-- JS can load menu items here later -->

                                <tr data-item-id="">
                                    <td>
                                        <div class="item-info">
                                            <img src="../../assets/images/burger.png" alt="Menu Item">
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

</body>
</html>