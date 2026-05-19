<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../../dirCommon/dbconnect.php';
include __DIR__ . '/../model/customerModel.php';

$search = trim($_GET['search'] ?? '');
$cuisine = trim($_GET['cuisine'] ?? '');
$restaurants = getApprovedRestaurants($conn, $search, $cuisine);
$cuisines = getCuisineTypes($conn);

$pageTitle = 'BiteBuddy - Browse Restaurants';
$activePage = 'browse';
$basePath = '../../';
$extraCss = ['../css/browseResturants.css'];
include __DIR__ . '/../../dirCommon/header.php';

function customerAssetPath($path)
{
    if ($path == '') return '../assets/images/burger-culture.svg';
    if (strpos($path, 'Customer/') === 0 || strpos($path, 'restaurantManager/') === 0) {
        return '../../' . $path;
    }
    return $path;
}
?>

    <main class="page-wrap customer-page">
        <section class="customer-hero">
            <div>
                <span class="eyebrow">Customer ordering</span>
                <h1>Browse Restaurants</h1>
                <p>Choose a restaurant, add menu items, and place an order in a few simple steps.</p>
            </div>
            <a class="outline-action" href="orders.php">View My Orders</a>
        </section>

        <form class="customer-toolbar" method="get">
            <label>
                <span>Search restaurants</span>
                <input name="search" type="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Restaurant or cuisine">
            </label>
            <label>
                <span>Cuisine</span>
                <select name="cuisine">
                    <option value="">All cuisines</option>
                    <?php foreach ($cuisines as $type): ?>
                        <option value="<?php echo htmlspecialchars($type); ?>" <?php echo $cuisine === $type ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($type); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <button type="submit">Apply</button>
            <a href="browseResturants.php">Reset</a>
        </form>

        <section class="restaurant-section">
            <div class="section-heading">
                <h2>Restaurants Near You</h2>
                <span><?php echo count($restaurants); ?> results</span>
            </div>

            <?php if (count($restaurants) === 0): ?>
                <div class="empty-state">
                    <h3>No restaurants found</h3>
                    <p>Import the demo data or try a different search.</p>
                </div>
            <?php else: ?>
                <div class="restaurant-grid">
                    <?php foreach ($restaurants as $restaurant): ?>
                        <a class="restaurant-card" href="restaurant.php?id=<?php echo (int)$restaurant['id']; ?>">
                            <img src="<?php echo htmlspecialchars(customerAssetPath($restaurant['logo_path'])); ?>" alt="<?php echo htmlspecialchars($restaurant['name']); ?> food preview">
                            <div class="card-body">
                                <div class="card-top">
                                    <h3><?php echo htmlspecialchars($restaurant['name']); ?></h3>
                                    <span class="rating"><?php echo number_format((float)$restaurant['rating'], 1); ?></span>
                                </div>
                                <p><?php echo htmlspecialchars($restaurant['cuisine_type'] ?: 'Restaurant'); ?></p>
                                <div class="meta">
                                    <span><?php echo htmlspecialchars($restaurant['city']); ?></span>
                                    <span><?php echo $restaurant['is_open'] ? 'Open now' : 'Closed'; ?></span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

<?php include __DIR__ . '/../../dirCommon/footer.php'; ?>
