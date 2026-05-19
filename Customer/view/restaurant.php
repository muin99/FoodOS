<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../../dirCommon/dbconnect.php';
include __DIR__ . '/../model/customerModel.php';

$restaurantId = (int)($_GET['id'] ?? 0);
$restaurant = getRestaurantById($conn, $restaurantId);

if (!$restaurant) {
    header('Location: browseResturants.php');
    exit;
}

$items = getRestaurantMenu($conn, $restaurantId);
$groupedItems = [];
foreach ($items as $item) {
    $groupedItems[$item['category_name']][] = $item;
}

$pageTitle = 'BiteBuddy - ' . $restaurant['name'];
$activePage = 'browse';
$basePath = '../../';
$extraCss = ['../css/browseResturants.css'];
include __DIR__ . '/../../dirCommon/header.php';

function menuAssetPath($path)
{
    if ($path == '') return '../assets/images/burger-culture.svg';
    if (strpos($path, 'Customer/') === 0 || strpos($path, 'restaurantManager/') === 0) {
        return '../../' . $path;
    }
    return '../../restaurantManager/assets/' . $path;
}
?>

<main class="page-wrap customer-page">
    <section class="restaurant-detail-hero">
        <div>
            <a class="back-link" href="browseResturants.php">Back to restaurants</a>
            <h1><?php echo htmlspecialchars($restaurant['name']); ?></h1>
            <p><?php echo htmlspecialchars($restaurant['description'] ?: $restaurant['cuisine_type']); ?></p>
            <div class="meta hero-meta">
                <span><?php echo htmlspecialchars($restaurant['cuisine_type']); ?></span>
                <span><?php echo htmlspecialchars($restaurant['opening_hours'] ?? 'Open daily'); ?></span>
                <span><?php echo number_format((float)$restaurant['rating'], 1); ?> rating</span>
            </div>
        </div>
        <div class="restaurant-status <?php echo $restaurant['is_open'] ? 'open' : 'closed'; ?>">
            <?php echo $restaurant['is_open'] ? 'Open now' : 'Closed'; ?>
        </div>
    </section>

    <div class="menu-layout">
        <section class="menu-list">
            <?php if (count($groupedItems) === 0): ?>
                <div class="empty-state">
                    <h3>No menu items yet</h3>
                    <p>This restaurant has not added menu items.</p>
                </div>
            <?php endif; ?>

            <?php foreach ($groupedItems as $category => $categoryItems): ?>
                <div class="menu-category">
                    <h2><?php echo htmlspecialchars($category); ?></h2>
                    <div class="menu-grid">
                        <?php foreach ($categoryItems as $item): ?>
                            <article class="menu-item">
                                <img src="<?php echo htmlspecialchars(menuAssetPath($item['image_path'])); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                <div>
                                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                    <p><?php echo htmlspecialchars($item['description'] ?? 'Freshly prepared item.'); ?></p>
                                    <strong>৳<?php echo number_format((float)$item['price'], 2); ?></strong>
                                </div>
                                <button
                                    type="button"
                                    class="add-cart-btn"
                                    data-id="<?php echo (int)$item['id']; ?>"
                                    data-restaurant-id="<?php echo (int)$restaurant['id']; ?>"
                                    data-restaurant-name="<?php echo htmlspecialchars($restaurant['name']); ?>"
                                    data-name="<?php echo htmlspecialchars($item['name']); ?>"
                                    data-price="<?php echo (float)$item['price']; ?>">
                                    Add
                                </button>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>

        <aside class="cart-panel">
            <h2>Your Cart</h2>
            <div id="cartItems" class="cart-items"></div>
            <div class="cart-total">
                <span>Subtotal</span>
                <strong id="cartSubtotal">৳0.00</strong>
            </div>
            <a class="primary-action wide" href="checkout.php">Checkout</a>
        </aside>
    </div>
</main>

<script src="../assets/js/customerCart.js"></script>
<?php include __DIR__ . '/../../dirCommon/footer.php'; ?>
