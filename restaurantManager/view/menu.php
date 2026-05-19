<?php
session_start();
include '../../dirCommon/dbconnect.php'; // your DB connection

$restaurantId = 7; // your restaurant ID

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
$selectedCategoryId = $_GET['category_id'] ?? 1; // default to 1 (Burgers)

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
<?php if(count($menuItems) == 0): ?>
<tr><td colspan="4">No items in this category.</td></tr>
<?php else: ?>
<?php foreach($menuItems as $item): ?>
<tr data-item-id="<?= $item['id'] ?>">

    <!-- Item info -->
    <td>
        <div class="item-info">
             <div>
                <h4><?= htmlspecialchars($item['name']) ?></h4>
                <p><?= htmlspecialchars($item['description']) ?></p>
            </div>
        </div>
    </td>

    <!-- Price -->
    <td>
        <span class="price-text">৳<?= number_format($item['price'], 2) ?></span>
        <input type="number" step="0.01" class="price-input" value="<?= $item['price'] ?>" style="display:none;">
    </td>

    <!-- Availability -->
    <td>
        <span class="avail-text <?= $item['is_available'] ? 'available-badge' : 'unavailable-badge' ?>">
            <?= $item['is_available'] ? 'Available' : 'Unavailable' ?>
        </span>
        <input type="checkbox" class="avail-input" <?= $item['is_available'] ? 'checked' : '' ?> style="display:none;">
    </td>

    <!-- Actions -->
    <td>
        <button type="button" class="edit-btn">✏️</button>
        <button type="button" class="save-btn" style="display:none;">Save💾</button>
        <button type="button" class="delete-btn" data-item-id="<?= $item['id'] ?>">🗑️</button>
    </td>

</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>

<script>



document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const row = btn.closest('tr');
        // Hide Edit, show Save
        btn.style.display = 'none';
        row.querySelector('.save-btn').style.display = 'inline-block';
        // Hide static text, show inputs
        row.querySelector('.price-text').style.display = 'none';
        row.querySelector('.price-input').style.display = 'inline-block';
        row.querySelector('.avail-text').style.display = 'none';
        row.querySelector('.avail-input').style.display = 'inline-block';
    });
});

document.querySelectorAll('.save-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const row = btn.closest('tr');
        const itemId = row.dataset.itemId;
        const price = row.querySelector('.price-input').value;
        const isAvailable = row.querySelector('.avail-input').checked ? 1 : 0;

        const formData = new FormData();
        formData.append('id', itemId);
        formData.append('price', price);
        formData.append('is_available', isAvailable);

        fetch('../controller/menuControl.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            if(data.success){
                // Update text spans
                row.querySelector('.price-text').textContent = '৳' + parseFloat(price).toFixed(2);
                row.querySelector('.avail-text').textContent = isAvailable ? 'Available' : 'Unavailable';
                // Show static text, hide inputs
                row.querySelector('.price-text').style.display = 'inline';
                row.querySelector('.price-input').style.display = 'none';
                row.querySelector('.avail-text').style.display = 'inline';
                row.querySelector('.avail-input').style.display = 'none';
                // Show Edit, hide Save
                row.querySelector('.edit-btn').style.display = 'inline-block';
                btn.style.display = 'none';
            }
        });
    });
});




document.querySelectorAll('.save-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const row = btn.closest('tr');
        const itemId = row.dataset.itemId;
        const price = row.querySelector('.price-input').value;
        const isAvailable = row.querySelector('.avail-input').checked ? 1 : 0;

        const formData = new FormData();
        formData.append('id', itemId);
        formData.append('price', price);
        formData.append('is_available', isAvailable);
        formData.append('action', 'update'); // indicate update action

        fetch('../controller/menuControl.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            if(data.success){
                // update table text
                row.querySelector('.price-text').textContent = '৳' + parseFloat(price).toFixed(2);
                row.querySelector('.avail-text').textContent = isAvailable ? 'Available' : 'Unavailable';
                row.querySelector('.avail-text').className = isAvailable ? 'avail-text available-badge' : 'avail-text unavailable-badge';

                // hide inputs, show text
                row.querySelector('.price-text').style.display = 'inline';
                row.querySelector('.price-input').style.display = 'none';
                row.querySelector('.avail-text').style.display = 'inline';
                row.querySelector('.avail-input').style.display = 'none';
                row.querySelector('.edit-btn').style.display = 'inline-block';
                btn.style.display = 'none';
            }
        });
    });
});




document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        if(!confirm('Are you sure you want to delete this item?')) return;

        const itemId = btn.dataset.itemId;
        const formData = new FormData();
        formData.append('id', itemId);
        formData.append('action', 'delete');

        fetch('../controller/menuControl.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            if(data.success){
                btn.closest('tr').remove();
            }
        });
    });
});

</script>

<script>
let ascending = true; // initial sort order

document.getElementById('priceHeader').addEventListener('click', () => {
    const table = document.querySelector('.menu-items-table tbody'); // tbody selector
    const rows = Array.from(table.querySelectorAll('tr'));

    rows.sort((a, b) => {
        const priceA = parseFloat(a.querySelector('.price-text').textContent.replace(/[^\d.]/g, ''));
        const priceB = parseFloat(b.querySelector('.price-text').textContent.replace(/[^\d.]/g, ''));
        return ascending ? priceA - priceB : priceB - priceA;
    });

    // Remove all rows and re-add sorted
    rows.forEach(row => table.appendChild(row));

    ascending = !ascending;

    // toggle arrow
    document.getElementById('priceHeader').innerHTML = `Price ${ascending ? '&#9650;' : '&#9660;'}`;
});
</script>






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
        window.location.href = "../../dirCommon/login.html";
    }
}
</script>

</body>
</html>