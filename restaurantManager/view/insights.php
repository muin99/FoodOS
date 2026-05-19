<?php
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

$totalOrders = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM orders WHERE restaurant_id = $restaurantId"
))['total'] ?? 0;

$totalReviews = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM reviews WHERE restaurant_id = $restaurantId"
))['total'] ?? 0;

$averageRating = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT AVG(rating) AS avg_rating FROM reviews WHERE restaurant_id = $restaurantId"
))['avg_rating'] ?? 0;

$averageRating = number_format($averageRating, 1);
$reviewGrowth =  100 ;



/* Best Seller */
$bestSellerResult = mysqli_query($conn, "
    SELECT m.name, SUM(oi.quantity) AS total_sold
    FROM order_items oi
    JOIN menu_items m ON oi.menu_item_id = m.id
    JOIN orders o ON oi.order_id = o.id
    WHERE o.restaurant_id = $restaurantId
    GROUP BY m.id
    ORDER BY total_sold DESC
    LIMIT 1
");

$bestSellerRow = mysqli_fetch_assoc($bestSellerResult);

$bestSeller = $bestSellerRow['name'] ?? '--';


/* Avg Delivery Time */
$avgDeliveryResult = mysqli_query($conn, "
    SELECT AVG(estimated_delivery_minutes) AS avg_time
    FROM orders
    WHERE restaurant_id = $restaurantId
");

$avgDeliveryRow = mysqli_fetch_assoc($avgDeliveryResult);

$avgDeliveryTime = round($avgDeliveryRow['avg_time'] ?? 0);


/* Customer Satisfaction */
$positiveReviews = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM reviews 
     WHERE restaurant_id = $restaurantId AND rating >= 4"
))['total'] ?? 0;

$customerSatisfaction = $totalReviews > 0
    ? round(($positiveReviews / $totalReviews) * 100)
    : 0;

    $rating5 = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM reviews WHERE restaurant_id = $restaurantId AND rating = 5"
))['total'] ?? 0;

$rating4 = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM reviews WHERE restaurant_id = $restaurantId AND rating = 4"
))['total'] ?? 0;

$rating3 = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM reviews WHERE restaurant_id = $restaurantId AND rating = 3"
))['total'] ?? 0;

$rating2 = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM reviews WHERE restaurant_id = $restaurantId AND rating = 2"
))['total'] ?? 0;

$rating1 = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM reviews WHERE restaurant_id = $restaurantId AND rating = 1"
))['total'] ?? 0;



$peakHourQuery = "
SELECT HOUR(created_at) AS peak_hour, COUNT(*) AS total_orders
FROM orders
WHERE restaurant_id = $restaurantId
GROUP BY HOUR(created_at)
ORDER BY total_orders DESC
LIMIT 1
";

$peakHourResult = mysqli_query($conn, $peakHourQuery);

$peakHour = "--";

if(mysqli_num_rows($peakHourResult) > 0){

    $peakData = mysqli_fetch_assoc($peakHourResult);

    $hour = $peakData['peak_hour'];

    if($hour == 0){
        $peakHour = "12 AM";
    } elseif($hour < 12){
        $peakHour = $hour . " AM";
    } elseif($hour == 12){
        $peakHour = "12 PM";
    } else {
        $peakHour = ($hour - 12) . " PM";
    }
}

$rating5Percent = $totalReviews > 0 ? round(($rating5 / $totalReviews) * 100) : 0;
$rating4Percent = $totalReviews > 0 ? round(($rating4 / $totalReviews) * 100) : 0;
$rating3Percent = $totalReviews > 0 ? round(($rating3 / $totalReviews) * 100) : 0;
$rating2Percent = $totalReviews > 0 ? round(($rating2 / $totalReviews) * 100) : 0;
$rating1Percent = $totalReviews > 0 ? round(($rating1 / $totalReviews) * 100) : 0;




$weeklyOrders = [
    'Mon' => 0,
    'Tue' => 0,
    'Wed' => 0,
    'Thu' => 0,
    'Fri' => 0,
    'Sat' => 0,
    'Sun' => 0
];

$weeklyQuery = "
SELECT DAYNAME(created_at) AS order_day, COUNT(*) AS total
FROM orders
WHERE restaurant_id = $restaurantId
GROUP BY DAYNAME(created_at)
";

$weeklyResult = mysqli_query($conn, $weeklyQuery);

while ($row = mysqli_fetch_assoc($weeklyResult)) {
    $day = substr($row['order_day'], 0, 3);
    $weeklyOrders[$day] = $row['total'];
}

$maxWeeklyOrder = max($weeklyOrders);
if ($maxWeeklyOrder == 0) {
    $maxWeeklyOrder = 1;
}





?>











<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Food OS - Insights</title>

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
                    <h3 id="averageRating"><?php echo $averageRating; ?></h3>
                    <span>Out of 5</span>
                </div>

                <div class="insight-card" data-card="total-orders">
                    <i class="fa-solid fa-bag-shopping orange-circle"></i>
                    <p>Total Orders</p>
                   <h3 id="insightTotalOrders"><?php echo $totalOrders; ?></h3>
                    <span class="green-text" id="orderGrowthText">Waiting for data</span>
                </div>

                <div class="insight-card" data-card="total-reviews">
                    <i class="fa-solid fa-message purple-circle"></i>
                        <p>Total Reviews</p>
                        <h3 id="totalReviews"><?php echo $totalReviews; ?></h3>
                           <span class="green-text" id="reviewGrowthText">
                                   <?php echo $reviewGrowth; ?>% reviews loaded
                           </span>
                </div>

                <div class="insight-card" data-card="revenue-growth">
                     <i class="fa-solid fa-arrow-trend-up green-circle"></i>
                    <p>Revenue Growth</p>
                     <h3 id="revenueGrowth">64%</h3>
                     <span>vs last week</span>
                </div>

            </section>

            <section class="insights-grid">

                <div class="insight-box chart-box">

                    <h2>Weekly Orders</h2>

                    <div class="bar-chart" id="weeklyOrdersChart">

                        <!-- JS can change bar heights -->

                        <div class="bar-item" data-day="Mon">
                            <div class="bar" id="barMon" style="height: <?php echo ($weeklyOrders['Mon'] / $maxWeeklyOrder) * 190; ?>px;"></div><span>Mon</span>
                        </div>

                        <div class="bar-item" data-day="Tue">
                          <div class="bar" id="barTue" style="height: <?php echo ($weeklyOrders['Tue'] / $maxWeeklyOrder) * 190; ?>px;"></div>  <span>Tue</span>
                        </div>

                        <div class="bar-item" data-day="Wed">
                            <div class="bar" id="barWed" style="height: <?php echo ($weeklyOrders['Wed'] / $maxWeeklyOrder) * 190; ?>px;"></div><span>Wed</span>
                        </div>

                        <div class="bar-item" data-day="Thu">
                           <div class="bar" id="barThu" style="height: <?php echo ($weeklyOrders['Thu'] / $maxWeeklyOrder) * 190; ?>px;"></div> <span>Thu</span>
                        </div>

                        <div class="bar-item" data-day="Fri">
                            <div class="bar" id="barFri" style="height: <?php echo ($weeklyOrders['Fri'] / $maxWeeklyOrder) * 190; ?>px;"></div><span>Fri</span>
                        </div>

                        <div class="bar-item" data-day="Sat">
                            <div class="bar" id="barSat" style="height: <?php echo ($weeklyOrders['Sat'] / $maxWeeklyOrder) * 190; ?>px;"></div> <span>Sat</span>
                        </div>

                        <div class="bar-item" data-day="Sun">
                            <div class="bar" id="barSun" style="height: <?php echo ($weeklyOrders['Sun'] / $maxWeeklyOrder) * 190; ?>px;"></div> <span>Sun</span>
                        </div>

                    </div>

                </div>

                <div class="insight-box quick-box">

                    <h2>Quick Analytics</h2>

                    <div class="quick-row">
                        <i class="fa-regular fa-clock"></i>
                        <div>
                            <p>Peak Hour</p>
                            <h3><?php echo $peakHour; ?></h3>
                        </div>
                    </div>

                    <div class="quick-row">
                        <i class="fa-solid fa-trophy"></i>
                        <div>
                            <p>Best Seller</p>
                            <h3 id="bestSeller"><?php echo $bestSeller; ?></h3>
                        </div>
                    </div>

                    <div class="quick-row">
                        <i class="fa-solid fa-motorcycle"></i>
                        <div>
                            <p>Avg Delivery Time</p>
                           <h3 id="avgDeliveryTime"><?php echo $avgDeliveryTime; ?> min</h3>
                        </div>
                    </div>

                    <div class="quick-row">
                        <i class="fa-regular fa-face-smile"></i>
                        <div>
                            <p>Customer Satisfaction</p>
                           <h3 id="customerSatisfaction"><?php echo $customerSatisfaction; ?>%</h3>
                        </div>
                    </div>

                </div>

            </section>

            <section class="insights-grid bottom-grid">

                <div class="insight-box reviews-box">

                    <div class="box-title">
                        <h2>Recent Customer Reviews</h2>
                        <a href="#" id="viewAllReviews">View All</a>
                    </div>

                    <div id="reviewsContainer">

                        <!-- JS can load reviews here later -->

                       <?php
$reviewQuery = "
SELECT reviews.*, users.name
FROM reviews
JOIN users ON reviews.customer_id = users.id
WHERE reviews.restaurant_id = $restaurantId
ORDER BY reviews.created_at DESC
LIMIT 100
";

$reviewResult = mysqli_query($conn, $reviewQuery);

if(mysqli_num_rows($reviewResult) > 0){

    while($review = mysqli_fetch_assoc($reviewResult)){
?>

<div class="review-item review-hidden">

    <div class="review-avatar">
        <?php echo strtoupper(substr($review['name'],0,1)); ?>
    </div>

    <div class="review-content">

        <h4><?php echo $review['name']; ?></h4>

        <p>
            <?php echo $review['comment']; ?>
        </p>

        <span>
            ⭐ <?php echo $review['rating']; ?>/5
        </span>

    </div>

</div>

<?php
    }

}else{
    echo "<p>No reviews found.</p>";
}
?>

                    </div>

                </div>

                <div>

                    <div class="insight-box rating-box">

                        <h2>Rating Breakdown</h2>

                        <div class="rating-line">
                            <span>5 Star</span>
                            <b id="fiveStarBar" style="width:<?php echo $rating5Percent; ?>%"></b>
                            <p id="fiveStarPercent"><?php echo $rating5Percent; ?>%</p>
                        </div>

                        <div class="rating-line">
                            <span>4 Star</span>
                            <b id="fourStarBar" style="width:<?php echo $rating4Percent; ?>%"></b>
                            <p id="fourStarPercent"><?php echo $rating4Percent; ?>%</p>
                        </div>

                        <div class="rating-line">
                            <span>3 Star</span>
                            <b id="threeStarBar" style="width:<?php echo $rating3Percent; ?>%"></b>
                            <p id="threeStarPercent"><?php echo $rating3Percent; ?>%</p>
                        </div>

                        <div class="rating-line">
                            <span>2 Star</span>
                            <b id="twoStarBar" style="width:<?php echo $rating2Percent; ?>%"></b>
                            <p id="twoStarPercent"><?php echo $rating2Percent; ?>%</p>
                        </div>

                        <div class="rating-line">
                            <span>1 Star</span>
                            <b id="oneStarBar" style="width:<?php echo $rating1Percent; ?>%"></b>
                            <p id="oneStarPercent"><?php echo $rating1Percent; ?>%</p>
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


<script>
function confirmLogout(event) {
    event.preventDefault();

    let logoutConfirm = confirm("Are you sure you want to logout?");

    if (logoutConfirm) {
        window.location.href = "../controller/logout.php";
    }
}
</script>



<script>
document.getElementById('viewAllReviews').addEventListener('click', function(event) {
    event.preventDefault();

    const reviewBox = document.querySelector('.reviews-box');

    reviewBox.classList.toggle('expanded');

    if (reviewBox.classList.contains('expanded')) {
        this.innerText = 'Show Less';
    } else {
        this.innerText = 'View All';
    }
});
</script>

</body>
</html>
