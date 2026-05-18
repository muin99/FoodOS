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

        <section class="profile-page">

            <div class="profile-title">
                <h1>Restaurant Profile</h1>
                <p>Manage restaurant information and settings.</p>
            </div>

            <div class="profile-grid">

                <!-- Restaurant Information -->
                <div class="profile-card restaurant-info-card">

                    <h2>Restaurant Information</h2>

                    <div class="profile-form-area">

                        <div class="logo-upload">

                            <img
                                src="../../assets/images/burger.png"
                                alt="Restaurant Logo"
                                id="restaurantLogo"
                            >

                            <button
                                type="button"
                                id="changeLogoBtn"
                                data-action="change-logo"
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
                                    value="Khana Kitchen"
                                    data-field="name"
                                >

                                <i class="fa-solid fa-pen"></i>
                            </div>

                            <label>Cuisine Type</label>

                            <div class="input-box">
                                <input
                                    type="text"
                                    id="cuisineType"
                                    value="Fast Food"
                                    data-field="cuisine_type"
                                >

                                <i class="fa-solid fa-pen"></i>
                            </div>

                            <label>Description</label>

                            <div class="input-box textarea-box">
                                <textarea
                                    id="restaurantDescription"
                                    data-field="description"
                                >Fast food restaurant</textarea>

                                <i class="fa-solid fa-pen"></i>
                            </div>

                            <label>Address</label>

                            <div class="input-box">
                                <input
                                    type="text"
                                    id="restaurantAddress"
                                    value="Dhanmondi, Dhaka"
                                    data-field="address"
                                >

                                <i class="fa-solid fa-pen"></i>
                            </div>

                            <label>City</label>

                            <div class="input-box">
                                <input
                                    type="text"
                                    id="restaurantCity"
                                    value="Dhaka"
                                    data-field="city"
                                >

                                <i class="fa-solid fa-pen"></i>
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
                            <p id="mondayHours">10:00 AM - 11:00 PM</p>
                        </div>

                        <div>
                            <span>Tuesday</span>
                            <p id="tuesdayHours">10:00 AM - 11:00 PM</p>
                        </div>

                        <div>
                            <span>Wednesday</span>
                            <p id="wednesdayHours">10:00 AM - 11:00 PM</p>
                        </div>

                        <div>
                            <span>Thursday</span>
                            <p id="thursdayHours">10:00 AM - 11:00 PM</p>
                        </div>

                        <div>
                            <span>Friday</span>
                            <p id="fridayHours">10:00 AM - 11:00 PM</p>
                        </div>

                        <div>
                            <span>Saturday</span>
                            <p id="saturdayHours">10:00 AM - 11:00 PM</p>
                        </div>

                        <div>
                            <span>Sunday</span>
                            <p id="sundayHours">10:00 AM - 11:00 PM</p>
                        </div>

                    </div>

                    <button
                        type="button"
                        class="edit-hours-btn"
                        id="editHoursBtn"
                        data-action="edit-hours"
                    >
                        Edit Hours
                    </button>

                </div>

                <!-- Delivery Radius -->
                <div class="profile-small-card">

                    <div>
                        <h3>Delivery Radius</h3>

                        <p>
                            <span
                                id="deliveryRadius"
                                data-field="delivery_radius_km"
                            >
                                5
                            </span>

                            KM
                        </p>
                    </div>

                    <button
                        type="button"
                        id="editRadiusBtn"
                        data-action="edit-radius"
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
                            data-field="is_open"
                        >
                            Open
                        </p>
                    </div>

                    <label class="switch">

                        <input
                            type="checkbox"
                            checked
                            id="restaurantToggle"
                            data-action="toggle-restaurant-status"
                        >

                        <span class="slider"></span>

                    </label>

                </div>

                <!-- Manager Info -->
         
<div class="profile-small-card manager-mini-card">

    <div>
        <h3>Manager Info</h3>

        <p id="managerName" data-field="manager_name">
            Manager ID: 1
        </p>

        <span id="managerRole" data-field="manager_role">
            Restaurant Manager
        </span>
    </div>

    <button
        type="button"
        id="editManagerBtn"
        data-action="edit-manager"
    >
        Edit
    </button>

</div>

            </div>

        </section>

    </main>

</div>

</body>
</html>