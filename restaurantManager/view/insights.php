<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Food OS - Insights</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="../../assets/css/manager-dashboard.css">
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

            <a href="insights.php" class="active">
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

        <section class="insights-page">

            <div class="insights-header">

                <div>
                    <h1>Analysis & Reviews</h1>
                </div>

                <div class="online-pill">
                    <i class="fa-solid fa-circle"></i>
                    <span id="insightRestaurantStatus">Online</span>
                </div>

            </div>

            <section class="insight-cards">

                <div class="insight-card" data-card="average-rating">
                    <i class="fa-solid fa-star yellow-circle"></i>
                    <p>Average Rating</p>
                    <h3 id="averageRating">0.0</h3>
                    <span>Out of 5</span>
                </div>

                <div class="insight-card" data-card="total-orders">
                    <i class="fa-solid fa-bag-shopping orange-circle"></i>
                    <p>Total Orders</p>
                    <h3 id="insightTotalOrders">0</h3>
                    <span class="green-text" id="orderGrowthText">Waiting for data</span>
                </div>

                <div class="insight-card" data-card="total-reviews">
                    <i class="fa-solid fa-message purple-circle"></i>
                    <p>Total Reviews</p>
                    <h3 id="totalReviews">0</h3>
                    <span class="green-text" id="reviewGrowthText">Waiting for data</span>
                </div>

                <div class="insight-card" data-card="revenue-growth">
                    <i class="fa-solid fa-arrow-trend-up green-circle"></i>
                    <p>Revenue Growth</p>
                    <h3 id="revenueGrowth">0%</h3>
                    <span>vs last week</span>
                </div>

            </section>

            <section class="insights-grid">

                <div class="insight-box chart-box">

                    <h2>Weekly Orders</h2>

                    <div class="bar-chart" id="weeklyOrdersChart">

                        <!-- JS can change bar heights later -->

                        <div class="bar-item" data-day="Mon">
                            <div class="bar" id="barMon" style="height: 90px;"></div>
                            <span>Mon</span>
                        </div>

                        <div class="bar-item" data-day="Tue">
                            <div class="bar" id="barTue" style="height: 120px;"></div>
                            <span>Tue</span>
                        </div>

                        <div class="bar-item" data-day="Wed">
                            <div class="bar" id="barWed" style="height: 150px;"></div>
                            <span>Wed</span>
                        </div>

                        <div class="bar-item" data-day="Thu">
                            <div class="bar" id="barThu" style="height: 190px;"></div>
                            <span>Thu</span>
                        </div>

                        <div class="bar-item" data-day="Fri">
                            <div class="bar" id="barFri" style="height: 145px;"></div>
                            <span>Fri</span>
                        </div>

                        <div class="bar-item" data-day="Sat">
                            <div class="bar" id="barSat" style="height: 110px;"></div>
                            <span>Sat</span>
                        </div>

                        <div class="bar-item" data-day="Sun">
                            <div class="bar" id="barSun" style="height: 70px;"></div>
                            <span>Sun</span>
                        </div>

                    </div>

                </div>

                <div class="insight-box quick-box">

                    <h2>Quick Analytics</h2>

                    <div class="quick-row">
                        <i class="fa-regular fa-clock"></i>
                        <div>
                            <p>Peak Hour</p>
                            <h3 id="peakHour">--</h3>
                        </div>
                    </div>

                    <div class="quick-row">
                        <i class="fa-solid fa-trophy"></i>
                        <div>
                            <p>Best Seller</p>
                            <h3 id="bestSeller">--</h3>
                        </div>
                    </div>

                    <div class="quick-row">
                        <i class="fa-solid fa-motorcycle"></i>
                        <div>
                            <p>Avg Delivery Time</p>
                            <h3 id="avgDeliveryTime">--</h3>
                        </div>
                    </div>

                    <div class="quick-row">
                        <i class="fa-regular fa-face-smile"></i>
                        <div>
                            <p>Customer Satisfaction</p>
                            <h3 id="customerSatisfaction">0%</h3>
                        </div>
                    </div>

                </div>

            </section>

            <section class="insights-grid bottom-grid">

                <div class="insight-box reviews-box">

                    <div class="box-title">
                        <h2>Recent Customer Reviews</h2>
                        <a href="#" data-action="view-all-reviews">View All</a>
                    </div>

                    <div id="reviewsContainer">

                        <!-- JS can load reviews here later -->

                        <div class="review-row" data-review-id="">
                            <div class="review-avatar">?</div>

                            <div class="review-info">
                                <h4 id="reviewCustomerName">No review loaded</h4>
                                <small id="reviewDate">--</small>
                                <p id="reviewText">Customer reviews will appear here.</p>
                            </div>

                            <div class="review-stars" id="reviewStars">☆☆☆☆☆</div>

                            <button type="button" data-action="reply-review">
                                Reply
                            </button>
                        </div>

                    </div>

                </div>

                <div>

                    <div class="insight-box rating-box">

                        <h2>Rating Breakdown</h2>

                        <div class="rating-line">
                            <span>5 Star</span>
                            <div><b id="fiveStarBar" style="width:0%"></b></div>
                            <p id="fiveStarPercent">0%</p>
                        </div>

                        <div class="rating-line">
                            <span>4 Star</span>
                            <div><b id="fourStarBar" style="width:0%"></b></div>
                            <p id="fourStarPercent">0%</p>
                        </div>

                        <div class="rating-line">
                            <span>3 Star</span>
                            <div><b id="threeStarBar" style="width:0%"></b></div>
                            <p id="threeStarPercent">0%</p>
                        </div>

                        <div class="rating-line">
                            <span>2 Star</span>
                            <div><b id="twoStarBar" style="width:0%"></b></div>
                            <p id="twoStarPercent">0%</p>
                        </div>

                        <div class="rating-line">
                            <span>1 Star</span>
                            <div><b id="oneStarBar" style="width:0%"></b></div>
                            <p id="oneStarPercent">0%</p>
                        </div>

                    </div>

                    <div class="insight-box complaint-box">

                        <div class="box-title">
                            <h2>Recent Complaints</h2>
                            <a href="#" data-action="view-all-complaints">View All</a>
                        </div>

                        <div id="complaintsContainer">

                            <!-- JS can load complaints here later -->

                            <div class="complaint-row" data-complaint-id="">
                                <p>No complaints loaded</p>
                                <span class="pending">Waiting</span>
                            </div>

                        </div>

                    </div>

                </div>

            </section>

        </section>

    </main>

</div>

</body>
</html>