<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

include '../../dirCommon/dbconnect.php';
include '../model/customerModel.php';

if (($_SESSION['user_role'] ?? '') !== 'customer' || !isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login as a customer first.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    echo json_encode(['success' => false, 'message' => 'Invalid checkout data.']);
    exit;
}

$customerId = (int)$_SESSION['user_id'];
$restaurantId = (int)($input['restaurant_id'] ?? 0);
$items = $input['items'] ?? [];
$address = trim($input['delivery_address'] ?? '');
$city = trim($input['city'] ?? 'Dhaka');
$paymentMethod = trim($input['payment_method'] ?? 'Cash');

if ($address === '') {
    echo json_encode(['success' => false, 'message' => 'Delivery address is required.']);
    exit;
}

$orderId = createCustomerOrder($conn, $customerId, $restaurantId, $items, $address, $paymentMethod);

if ($orderId === false) {
    echo json_encode(['success' => false, 'message' => 'Order could not be placed.']);
    exit;
}

saveCustomerAddress($conn, $customerId, 'Checkout', $address, $city);

echo json_encode([
    'success' => true,
    'message' => 'Order placed successfully.',
    'order_id' => $orderId,
    'redirect' => 'orders.php'
]);
