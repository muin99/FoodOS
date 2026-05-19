<?php
session_start();

include '../../dirCommon/dbconnect.php';

$managerName = $_SESSION['user_name'] ?? 'Manager';
$managerId = 1;

$restaurantName = '';
$description = '';
$cuisineType = '';
$address = '';
$city = '';
$deliveryRadius = 5;
$isOpen = 1;

$sql = "SELECT * FROM restaurants WHERE manager_id = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $managerId);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$restaurant = mysqli_fetch_assoc($result);

if ($restaurant != null) {
    $restaurantName = $restaurant['name'];
    $description = $restaurant['description'];
    $cuisineType = $restaurant['cuisine_type'];
    $address = $restaurant['address'];
    $city = $restaurant['city'];
    $deliveryRadius = $restaurant['delivery_radius_km'];
    $isOpen = $restaurant['is_open'];
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Food OS - Profile</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

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

            <a href="profile.php" class="active">
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

        <section class="profile-page">

            <div class="profile-title">
                <h1>Restaurant Profile</h1>
            </div>

            <div class="profile-grid">

                <!-- Restaurant Information -->
                <div class="profile-card restaurant-info-card">

                    <h2>Restaurant Information</h2>

                    <div class="profile-form-area">

                        <div class="logo-upload">

                            <img
                                src="../assets/images/burger.png"
                                alt="Restaurant Logo"
                                id="restaurantLogo"
                            >

                            <button
                                type="button"
                                id="changeLogoBtn"
                            >
                                Change Logo
                            </button>

                        </div>

                        <div class="profile-form">

                            <label>Restaurant Name</label>

                            <div class="input-box">
                                <input
                                    type="text"
                                    id="restaurantName"
                                    value="<?php echo $restaurantName; ?>"
                                    readonly
                                >

                                <i class="fa-solid fa-pen edit-field"></i>
                            </div>

                            <label>Cuisine Type</label>

                            <div class="input-box">
                                <input
                                    type="text"
                                    id="cuisineType"
                                  value="<?php echo $cuisineType; ?>"
                                    readonly
                                >

                                <i class="fa-solid fa-pen edit-field"></i>
                            </div>

                            <label>Description</label>

                            <div class="input-box textarea-box">
                                <textarea
                                    id="restaurantDescription"
                                    readonly
                                ><?php echo $description; ?></textarea>

                                <i class="fa-solid fa-pen edit-field"></i>
                            </div>

                            <label>Address</label>

                            <div class="input-box">
                                <input
                                    type="text"
                                    id="restaurantAddress"
                                   value="<?php echo $address; ?>"
                                    readonly
                                >

                                <i class="fa-solid fa-pen edit-field"></i>
                            </div>

                            <label>City</label>

                            <div class="input-box">
                                <input
                                    type="text"
                                    id="restaurantCity"
                                   value="<?php echo $city; ?>"
                                    readonly
                                >

                                <i class="fa-solid fa-pen edit-field"></i>
                            </div>

                        </div>

                    </div>

                </div>

                <!-- Business Hours -->
                <div class="profile-card">

                    <h2>Business Hours</h2>

                    <div class="hours-list" id="businessHoursList">

                        <div>
                            <span>Monday</span>
                            <p>10:00 AM - 11:00 PM</p>
                        </div>

                        <div>
                            <span>Tuesday</span>
                            <p>10:00 AM - 11:00 PM</p>
                        </div>

                        <div>
                            <span>Wednesday</span>
                            <p>10:00 AM - 11:00 PM</p>
                        </div>

                        <div>
                            <span>Thursday</span>
                            <p>10:00 AM - 11:00 PM</p>
                        </div>

                        <div>
                            <span>Friday</span>
                            <p>10:00 AM - 11:00 PM</p>
                        </div>

                        <div>
                            <span>Saturday</span>
                            <p>10:00 AM - 11:00 PM</p>
                        </div>

                        <div>
                            <span>Sunday</span>
                            <p>10:00 AM - 11:00 PM</p>
                        </div>

                    </div>

                </div>

                <!-- Delivery Radius -->
                <div class="profile-small-card">

                    <div>
                        <h3>Delivery Radius</h3>

                        <p>
                            <span
                                id="deliveryRadius"
                                contenteditable="false"
                           ><?php echo $deliveryRadius; ?></span>
                            KM
                        </p>
                    </div>

                    <button
                        type="button"
                        id="editRadiusBtn"
                    >
                        Edit
                    </button>

                </div>

                <!-- Restaurant Status -->
                <div class="profile-small-card">

                    <div>
                        <h3>Restaurant Status</h3>

                        <p
                            class="open-text"
                            id="restaurantOpenText"
                        >
                            Open
                        </p>
                    </div>

                    <label class="switch">

                        <input
                            type="checkbox"
                            checked
                            id="restaurantToggle"
                        >

                        <span class="slider"></span>

                    </label>

                </div>

                <!-- Manager Info -->
                
            <div class="profile-small-card manager-mini-card">

                 <div>
                    <h3>Manager Info</h3>

               <h2 id="managerName">
                   <?php echo $managerName; ?>
              </h2>

                 <span id="managerRole">
                    Restaurant Manager
                  </span>
            </div>

    <div style="text-align: right;">
        <p style="font-size: 14px; color: #777;">Manager ID</p>

        <h2 style="font-size: 28px; color: #c51b05;">
            <?php echo $managerId; ?>
        </h2>
    </div>

</div>


                <button
                    type="button"
                    class="edit-hours-btn"
                    onclick="saveProfile()"
                >
                    Save Profile
                </button>

            </div>

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

const editButtons = document.querySelectorAll('.edit-field');

editButtons.forEach(function(button) {
    button.addEventListener('click', function() {
        const box = button.parentElement;
        const field = box.querySelector('input, textarea');

        if (field) {
            field.removeAttribute('readonly');
            field.focus();
        }
    });
});

document.getElementById('editRadiusBtn').addEventListener('click', function() {
    const radius = document.getElementById('deliveryRadius');

    radius.setAttribute('contenteditable', 'true');
    radius.focus();
});


document.getElementById('restaurantToggle').addEventListener('change', function() {
    const statusText = document.getElementById('restaurantOpenText');

    if (this.checked) {
        statusText.innerText = 'Open';
    } else {
        statusText.innerText = 'Closed';
    }
});

function saveProfile() {
    const formData = new FormData();

    formData.append('name', document.getElementById('restaurantName').value);
    formData.append('description', document.getElementById('restaurantDescription').value);
    formData.append('cuisine_type', document.getElementById('cuisineType').value);
    formData.append('address', document.getElementById('restaurantAddress').value);
    formData.append('city', document.getElementById('restaurantCity').value);
    formData.append('delivery_radius_km', document.getElementById('deliveryRadius').innerText.trim());
    formData.append('is_open', document.getElementById('restaurantToggle').checked ? 1 : 0);

    const xhr = new XMLHttpRequest();

    xhr.open('POST', '../controller/profileControl.php', true);

    xhr.onload = function() {
        const data = JSON.parse(xhr.responseText);
        alert(data.message);
    };

    xhr.send(formData);
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


</body>
</html>