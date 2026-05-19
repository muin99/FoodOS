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

$customerId = (int)$_SESSION['user_id'];
$profile = getCustomerProfile($conn, $customerId);
$addresses = getCustomerAddresses($conn, $customerId);
$orders = getCustomerOrders($conn, $customerId);

$pageTitle = 'BiteBuddy - My Profile';
$activePage = 'profile';
$basePath = '../../';
$extraCss = ['../css/browseResturants.css'];
include __DIR__ . '/../../dirCommon/header.php';
?>

<main class="page-wrap customer-page">
    <section class="customer-hero compact">
        <div>
            <span class="eyebrow">Account</span>
            <h1>My Profile</h1>
            <p>Update your basic account details and view saved addresses.</p>
        </div>
        <a class="outline-action" href="orders.php">My Orders</a>
    </section>

    <section class="profile-layout">
        <div class="checkout-card">
            <h2>Account details</h2>
            <form method="post" action="../controller/updateProfile.php">
                <label>
                    <span>Name</span>
                    <input name="name" value="<?php echo htmlspecialchars($profile['name'] ?? ''); ?>" required>
                </label>
                <label>
                    <span>Email</span>
                    <input value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>" disabled>
                </label>
                <label>
                    <span>Phone</span>
                    <input name="phone" value="<?php echo htmlspecialchars($profile['phone'] ?? ''); ?>">
                </label>
                <button type="submit">Save Profile</button>
            </form>
        </div>

        <div class="profile-summary">
            <div>
                <strong><?php echo count($orders); ?></strong>
                <span>Total orders</span>
            </div>
            <div>
                <strong><?php echo count($addresses); ?></strong>
                <span>Saved addresses</span>
            </div>
        </div>

        <div class="checkout-card">
            <h2>Saved addresses</h2>
            <?php if (count($addresses) === 0): ?>
                <p class="muted">No addresses saved yet. Your checkout address will be saved after your first order.</p>
            <?php else: ?>
                <div class="address-list">
                    <?php foreach ($addresses as $address): ?>
                        <div>
                            <strong><?php echo htmlspecialchars($address['label']); ?></strong>
                            <span><?php echo htmlspecialchars($address['address_line'] . ', ' . $address['city']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../../dirCommon/footer.php'; ?>
