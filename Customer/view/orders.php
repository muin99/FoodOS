<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../../dirCommon/dbconnect.php';
include __DIR__ . '/../model/customerModel.php';

if (($_SESSION['user_role'] ?? '') !== 'customer' || !isset($_SESSION['user_id'])) {
    header('Location: ../../dirCommon/login.html');
    exit;
}

$orders = getCustomerOrders($conn, (int)$_SESSION['user_id']);

$pageTitle = 'BiteBuddy - My Orders';
$activePage = 'orders';
$basePath = '../../';
$extraCss = ['../css/browseResturants.css'];
include __DIR__ . '/../../dirCommon/header.php';
?>

<main class="page-wrap customer-page">
    <section class="customer-hero compact">
        <div>
            <span class="eyebrow">Order history</span>
            <h1>My Orders</h1>
            <p>Track recent orders and check your order status.</p>
        </div>
        <a class="outline-action" href="browseResturants.php">Order again</a>
    </section>

    <?php if (count($orders) === 0): ?>
        <div class="empty-state">
            <h3>No orders yet</h3>
            <p>Start by choosing a restaurant from the browse page.</p>
            <a class="primary-action" href="browseResturants.php">Browse Restaurants</a>
        </div>
    <?php else: ?>
        <section class="order-list">
            <?php foreach ($orders as $order): ?>
                <article class="order-row">
                    <div>
                        <span class="order-id">Order #<?php echo (int)$order['id']; ?></span>
                        <h2><?php echo htmlspecialchars($order['restaurant_name']); ?></h2>
                        <p><?php echo htmlspecialchars($order['delivery_address']); ?></p>
                    </div>
                    <div class="order-meta">
                        <span class="status-badge"><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $order['status']))); ?></span>
                        <strong>৳<?php echo number_format((float)$order['total_amount'], 2); ?></strong>
                        <small><?php echo htmlspecialchars(date('M d, Y h:i A', strtotime($order['created_at']))); ?></small>
                    </div>
                    <?php if ($order['status'] === 'delivered'): ?>
                        <div class="review-panel">
                            <h3><?php echo $order['review_id'] ? 'Your review' : 'Review this order'; ?></h3>
                            <?php if (!empty($order['review_reply'])): ?>
                                <p class="review-reply">Manager reply: <?php echo htmlspecialchars($order['review_reply']); ?></p>
                            <?php endif; ?>
                            <form class="review-form">
                                <input type="hidden" name="order_id" value="<?php echo (int)$order['id']; ?>">
                                <label>
                                    <span>Rating</span>
                                    <select name="rating" required>
                                        <option value="">Choose rating</option>
                                        <?php for ($rating = 5; $rating >= 1; $rating--): ?>
                                            <option value="<?php echo $rating; ?>" <?php echo (int)($order['review_rating'] ?? 0) === $rating ? 'selected' : ''; ?>>
                                                <?php echo $rating; ?> star<?php echo $rating > 1 ? 's' : ''; ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </label>
                                <label>
                                    <span>Comment</span>
                                    <textarea name="comment" rows="3" required><?php echo htmlspecialchars($order['review_comment'] ?? ''); ?></textarea>
                                </label>
                                <button type="submit" class="primary-action"><?php echo $order['review_id'] ? 'Update Review' : 'Submit Review'; ?></button>
                                <p class="review-message"></p>
                            </form>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
</main>

<script>
document.querySelectorAll('.review-form').forEach(function(form) {
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        const message = form.querySelector('.review-message');
        const button = form.querySelector('button');
        button.disabled = true;

        fetch('../controller/saveReview.php', {
            method: 'POST',
            body: new FormData(form)
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            message.textContent = data.message || '';
            if (data.success) {
                setTimeout(function() {
                    location.reload();
                }, 500);
            } else {
                button.disabled = false;
            }
        })
        .catch(function() {
            message.textContent = 'Review could not be saved.';
            button.disabled = false;
        });
    });
});
</script>

<?php include __DIR__ . '/../../dirCommon/footer.php'; ?>
